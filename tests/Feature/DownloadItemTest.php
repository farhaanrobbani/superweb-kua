<?php

namespace Tests\Feature;

use App\Models\DownloadItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadItemTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_OPERATOR]);
    }

    public function test_staff_can_create_download_item_with_file(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->post(route('download-items.store'), [
                'title' => 'Formulir Pengajuan',
                'description' => 'Formulir terbaru.',
                'category' => 'Formulir',
                'file' => UploadedFile::fake()->create('formulir.pdf', 100, 'application/pdf'),
                'active' => 1,
                'sort_order' => 1,
            ])
            ->assertRedirect(route('download-items.index'));

        $item = DownloadItem::where('title', 'Formulir Pengajuan')->first();
        $this->assertNotNull($item);
        $this->assertSame('Formulir', $item->category);
        $this->assertTrue($item->active);
        $this->assertSame(1, $item->sort_order);
        Storage::disk('public')->assertExists($item->file);
    }

    public function test_staff_can_create_download_item_with_external_url(): void
    {
        $this->actingAs($this->user)
            ->post(route('download-items.store'), [
                'title' => 'Brosur Digital',
                'external_url' => 'https://example.com/brosur.pdf',
                'active' => 1,
            ])
            ->assertRedirect(route('download-items.index'));

        $this->assertDatabaseHas('download_items', [
            'title' => 'Brosur Digital',
            'file' => null,
            'external_url' => 'https://example.com/brosur.pdf',
        ]);
    }

    public function test_download_item_requires_file_or_external_url(): void
    {
        $this->actingAs($this->user)
            ->post(route('download-items.store'), [
                'title' => 'Tanpa Sumber',
                'active' => 1,
            ])
            ->assertSessionHasErrors('file');
    }

    public function test_staff_can_replace_file_and_old_file_is_deleted(): void
    {
        Storage::fake('public');

        $oldPath = UploadedFile::fake()->create('lama.pdf', 100, 'application/pdf')->store('downloads', 'public');
        $item = DownloadItem::factory()->create(['file' => $oldPath]);

        $this->actingAs($this->user)
            ->put(route('download-items.update', $item), [
                'title' => $item->title,
                'file' => UploadedFile::fake()->create('baru.pdf', 100, 'application/pdf'),
                'active' => 1,
            ])
            ->assertRedirect(route('download-items.index'));

        $item->refresh();
        $this->assertNotSame($oldPath, $item->file);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($item->file);
    }

    public function test_staff_can_remove_file(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->create('hapus.pdf', 100, 'application/pdf')->store('downloads', 'public');
        $item = DownloadItem::factory()->create(['file' => $path, 'external_url' => null]);

        $this->actingAs($this->user)
            ->put(route('download-items.update', $item), [
                'title' => $item->title,
                'external_url' => 'https://example.com/brosur.pdf',
                'file_hapus' => '1',
                'active' => 1,
            ])
            ->assertRedirect(route('download-items.index'));

        $this->assertNull($item->fresh()->file);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_deleting_download_item_removes_file(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->create('hapus.pdf', 100, 'application/pdf')->store('downloads', 'public');
        $item = DownloadItem::factory()->create(['file' => $path]);

        $this->actingAs($this->user)
            ->delete(route('download-items.destroy', $item))
            ->assertRedirect(route('download-items.index'));

        $this->assertDatabaseMissing('download_items', ['id' => $item->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_forbidden_file_extension_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->post(route('download-items.store'), [
                'title' => 'Eksekutabel',
                'file' => UploadedFile::fake()->create('payload.exe', 100),
                'active' => 1,
            ])
            ->assertSessionHasErrors('file');
    }

    public function test_public_index_groups_active_items_by_category(): void
    {
        DownloadItem::factory()->create(['title' => 'Form A', 'category' => 'Formulir', 'active' => true]);
        DownloadItem::factory()->create(['title' => 'Form B', 'category' => 'Formulir', 'active' => true]);
        DownloadItem::factory()->external()->create(['title' => 'Brosur', 'category' => 'Brosur', 'active' => true]);
        DownloadItem::factory()->create(['title' => 'Draf', 'active' => false]);
        DownloadItem::factory()->create(['title' => 'Tanpa Kategori', 'category' => null, 'active' => true]);

        $this->get(route('unduhan.index'))
            ->assertOk()
            ->assertSee('Form A')
            ->assertSee('Form B')
            ->assertSee('Brosur')
            ->assertSee('Formulir')
            ->assertSee('Tanpa Kategori')
            ->assertSee('Lainnya')
            ->assertDontSee('Draf');
    }

    public function test_public_can_download_file(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('downloads/surat.pdf', 'isi pdf');
        $item = DownloadItem::factory()->create(['title' => 'Surat Resmi', 'file' => 'downloads/surat.pdf', 'active' => true]);

        $this->get(route('unduhan.unduh', $item))
            ->assertOk()
            ->assertDownload('surat-resmi.pdf');
    }

    public function test_public_download_of_inactive_item_is_404(): void
    {
        Storage::fake('public');

        $item = DownloadItem::factory()->create(['file' => 'downloads/surat.pdf', 'active' => false]);

        $this->get(route('unduhan.unduh', $item))->assertNotFound();
    }

    public function test_public_download_of_external_url_item_is_404(): void
    {
        $item = DownloadItem::factory()->external()->create(['active' => true]);

        $this->get(route('unduhan.unduh', $item))->assertNotFound();
    }

    public function test_public_download_of_missing_file_is_404(): void
    {
        Storage::fake('public');

        $item = DownloadItem::factory()->create(['file' => 'downloads/tidak-ada.pdf', 'active' => true]);

        $this->get(route('unduhan.unduh', $item))->assertNotFound();
    }
}
