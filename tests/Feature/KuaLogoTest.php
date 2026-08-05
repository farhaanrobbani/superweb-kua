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

    public function test_admin_can_set_jam_layanan_and_landing_shows_it(): void
    {
        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'jam_layanan' => 'Senin - Sabtu' . "\n" . '07.30 - 14.30 WIB',
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $this->assertSame("Senin - Sabtu\n07.30 - 14.30 WIB", KuaSetting::get('jam_layanan'));

        $this->get('/')
            ->assertOk()
            ->assertSee('Senin - Sabtu')
            ->assertSee('07.30 - 14.30 WIB')
            ->assertDontSee('08.00 – 15.00 WIB');
    }

    public function test_landing_footer_falls_back_to_default_jam_layanan(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Senin – Jumat')
            ->assertSee('08.00 – 15.00 WIB');
    }

    public function test_admin_navbar_shows_kua_logo(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo.png', 300, 300)->store('logos', 'public');
        KuaSetting::set('logo_path', $path);

        $this->actingAs($this->user)
            ->get(route('dashboard'))
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
