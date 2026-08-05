<?php

namespace Tests\Feature;

use App\Models\KuaSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KuaHeroTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public function test_staff_can_upload_hero(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'hero' => UploadedFile::fake()->image('hero.png', 1280, 540),
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $path = KuaSetting::get('hero_path');

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_staff_can_replace_hero_and_old_file_is_deleted(): void
    {
        Storage::fake('public');

        $oldPath = UploadedFile::fake()->image('lama.png', 1280, 540)->store('heroes', 'public');
        KuaSetting::set('hero_path', $oldPath);

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'hero' => UploadedFile::fake()->image('baru.png', 1280, 540),
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $newPath = KuaSetting::get('hero_path');

        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_staff_can_delete_hero(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('hero.png', 1280, 540)->store('heroes', 'public');
        KuaSetting::set('hero_path', $path);

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'hero_hapus' => '1',
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $this->assertSame('', KuaSetting::get('hero_path'));
        Storage::disk('public')->assertMissing($path);
    }

    public function test_invalid_hero_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'hero' => UploadedFile::fake()->create('hero.txt', 100),
            ]))
            ->assertSessionHasErrors('hero');

        $this->assertNull(KuaSetting::get('hero_path'));
    }

    public function test_landing_page_shows_hero_when_available(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('hero.png', 1280, 540)->store('heroes', 'public');
        KuaSetting::set('hero_path', $path);

        $this->get('/')
            ->assertOk()
            ->assertSee('storage/heroes/', false);
    }

    public function test_landing_page_hides_hero_when_absent(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('storage/heroes/', false);
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
