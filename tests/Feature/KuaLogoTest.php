<?php

namespace Tests\Feature;

use App\Models\KuaSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KuaLogoTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public function test_staff_can_upload_logo(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'logo' => UploadedFile::fake()->image('logo.png', 300, 300),
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $path = KuaSetting::get('logo_path');

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_staff_can_replace_logo_and_old_file_is_deleted(): void
    {
        Storage::fake('public');

        $oldPath = UploadedFile::fake()->image('lama.png', 300, 300)->store('logos', 'public');
        KuaSetting::set('logo_path', $oldPath);

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'logo' => UploadedFile::fake()->image('baru.png', 300, 300),
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $newPath = KuaSetting::get('logo_path');

        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_staff_can_delete_logo(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo.png', 300, 300)->store('logos', 'public');
        KuaSetting::set('logo_path', $path);

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'logo_hapus' => '1',
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $this->assertSame('', KuaSetting::get('logo_path'));
        Storage::disk('public')->assertMissing($path);
    }

    public function test_invalid_logo_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'logo' => UploadedFile::fake()->create('logo.txt', 100),
            ]))
            ->assertSessionHasErrors('logo');

        $this->assertNull(KuaSetting::get('logo_path'));
    }

    public function test_landing_page_shows_logo_when_available(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo.png', 300, 300)->store('logos', 'public');
        KuaSetting::set('logo_path', $path);

        $this->get('/')
            ->assertOk()
            ->assertSee('storage/logos/', false);
    }

    private function basePayload(array $extra = []): array
    {
        return array_merge([
            'instansi' => 'KUA Contoh',
            'alamat' => 'Jl. Contoh No. 1',
            'kepala_nama' => 'H. Contoh',
        ], $extra);
    }
}
