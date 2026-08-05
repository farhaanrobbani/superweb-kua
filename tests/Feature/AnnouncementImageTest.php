<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnnouncementImageTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public function test_staff_can_create_announcement_with_cover_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->post(route('announcements.store'), [
                'title' => 'Libur Nasioal',
                'content' => '<p>KUA tutup.</p>',
                'image' => UploadedFile::fake()->image('cover.png', 800, 400),
                'active' => 1,
            ])
            ->assertRedirect(route('announcements.index'));

        $announcement = Announcement::where('title', 'Libur Nasioal')->first();
        $this->assertNotNull($announcement);
        $this->assertNotNull($announcement->image);
        Storage::disk('public')->assertExists($announcement->image);
    }

    public function test_staff_can_replace_cover_image_and_old_file_is_deleted(): void
    {
        Storage::fake('public');

        $oldPath = UploadedFile::fake()->image('lama.png', 300, 200)->store('announcements/covers', 'public');
        $announcement = Announcement::factory()->create(['image' => $oldPath]);

        $this->actingAs($this->user)
            ->put(route('announcements.update', $announcement), [
                'title' => $announcement->title,
                'content' => $announcement->content,
                'image' => UploadedFile::fake()->image('baru.png', 400, 300),
                'active' => 1,
            ])
            ->assertRedirect(route('announcements.index'));

        $announcement->refresh();
        $this->assertNotSame($oldPath, $announcement->image);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($announcement->image);
    }

    public function test_staff_can_remove_cover_image(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('cover.png', 300, 200)->store('announcements/covers', 'public');
        $announcement = Announcement::factory()->create(['image' => $path]);

        $this->actingAs($this->user)
            ->put(route('announcements.update', $announcement), [
                'title' => $announcement->title,
                'content' => $announcement->content,
                'image_hapus' => '1',
                'active' => 1,
            ])
            ->assertRedirect(route('announcements.index'));

        $this->assertNull($announcement->fresh()->image);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_deleting_announcement_removes_cover_file(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('cover.png', 300, 200)->store('announcements/covers', 'public');
        $announcement = Announcement::factory()->create(['image' => $path]);

        $this->actingAs($this->user)
            ->delete(route('announcements.destroy', $announcement))
            ->assertRedirect(route('announcements.index'));

        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_invalid_cover_image_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->post(route('announcements.store'), [
                'title' => 'Salah',
                'content' => 'isi',
                'image' => UploadedFile::fake()->create('dokumen.txt', 100),
            ])
            ->assertSessionHasErrors('image');
    }

    public function test_content_is_sanitized_on_store(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->post(route('announcements.store'), [
                'title' => 'Sanitasi',
                'content' => '<p>Selamat <strong>libur</strong> &amp; sukses.</p><script>alert(1)</script><img src="x" onerror="alert(2)">',
                'active' => 1,
            ])
            ->assertRedirect(route('announcements.index'));

        $announcement = Announcement::where('title', 'Sanitasi')->first();

        $this->assertStringNotContainsString('script', $announcement->content);
        $this->assertStringNotContainsString('onerror', $announcement->content);
        $this->assertStringContainsString('<strong>libur</strong>', $announcement->content);
    }

    public function test_inline_image_upload_returns_url(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post(route('announcements.gambar'), [
                'upload' => UploadedFile::fake()->image('inline.png', 200, 200),
            ])
            ->assertOk()
            ->assertJsonStructure(['url']);

        $path = str_replace('/storage/', '', parse_url($response->json('url'), PHP_URL_PATH));
        Storage::disk('public')->assertExists($path);
        $this->assertStringStartsWith('announcements/content/', $path);
    }

    public function test_inline_image_upload_requires_auth(): void
    {
        Storage::fake('public');

        $this->post(route('announcements.gambar'), [
            'upload' => UploadedFile::fake()->image('inline.png', 200, 200),
        ])->assertRedirect(route('login'));
    }

    public function test_inline_image_upload_rejects_non_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->post(route('announcements.gambar'), [
                'upload' => UploadedFile::fake()->create('dokumen.txt', 100),
            ])
            ->assertSessionHasErrors('upload');
    }

    public function test_public_detail_renders_html_and_cover(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('cover.png', 800, 400)->store('announcements/covers', 'public');
        $announcement = Announcement::factory()->create([
            'title' => 'Detail Kaya',
            'content' => '<p>Paragraf <strong>penting</strong>.</p><ul><li>satu</li></ul>',
            'image' => $path,
            'active' => true,
        ]);

        $this->get(route('pengumuman.show', $announcement))
            ->assertOk()
            ->assertSee('Paragraf <strong>penting</strong>', false)
            ->assertSee('<li>satu</li>', false)
            ->assertSee('storage/announcements/covers/', false);
    }

    public function test_public_index_shows_cover_thumbnail(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('cover.png', 800, 400)->store('announcements/covers', 'public');
        $announcement = Announcement::factory()->create([
            'title' => 'Dengan Sampul',
            'content' => 'isi pengumuman.',
            'image' => $path,
            'active' => true,
        ]);

        $this->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('storage/announcements/covers/', false)
            ->assertSee('Dengan Sampul');
    }

    public function test_public_detail_strips_script_from_stored_content(): void
    {
        Storage::fake('public');

        $announcement = Announcement::factory()->create([
            'title' => 'Aman',
            'content' => '<script>alert(1)</script><p>Teks aman.</p>',
            'active' => true,
        ]);

        $this->get(route('pengumuman.show', $announcement))
            ->assertOk()
            ->assertDontSee('<script>')
            ->assertSee('<p>Teks aman.</p>', false);
    }
}
