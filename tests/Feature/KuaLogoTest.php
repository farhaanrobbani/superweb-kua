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

    public function test_staff_can_upload_logo2(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'logo2' => UploadedFile::fake()->image('logo2.png', 300, 300),
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $path = KuaSetting::get('logo2_path');

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_staff_can_delete_logo2(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo2.png', 300, 300)->store('logos2', 'public');
        KuaSetting::set('logo2_path', $path);

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'logo2_hapus' => '1',
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $this->assertSame('', KuaSetting::get('logo2_path'));
        Storage::disk('public')->assertMissing($path);
    }

    public function test_invalid_logo2_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'logo2' => UploadedFile::fake()->create('logo2.txt', 100),
            ]))
            ->assertSessionHasErrors('logo2');

        $this->assertNull(KuaSetting::get('logo2_path'));
    }

    public function test_staff_can_choose_kop_logo_and_set_kop_text(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo2.png', 300, 300)->store('logos2', 'public');
        KuaSetting::set('logo2_path', $path);

        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'kop_logo' => 'logo2',
                'kop_teks' => "#KUA KECAMATAN CONTOH\n##KECAMATAN CONTOH KABUPATEN CONTOH\nJl. Contoh No. 1",
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $this->assertSame('logo2', KuaSetting::get('kop_logo'));
        $this->assertSame("#KUA KECAMATAN CONTOH\n##KECAMATAN CONTOH KABUPATEN CONTOH\nJl. Contoh No. 1", KuaSetting::get('kop_teks'));
    }

    public function test_staff_can_set_kop_font_sizes(): void
    {
        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'kop_ukuran_judul' => '20',
                'kop_ukuran_sub' => '14',
                'kop_ukuran_sub2' => '12',
                'kop_ukuran_baris' => '11',
            ]))
            ->assertRedirect(route('kua-settings.edit'));

        $this->assertSame('20', KuaSetting::get('kop_ukuran_judul'));
        $this->assertSame('14', KuaSetting::get('kop_ukuran_sub'));
        $this->assertSame('12', KuaSetting::get('kop_ukuran_sub2'));
        $this->assertSame('11', KuaSetting::get('kop_ukuran_baris'));
    }

    public function test_kop_font_size_out_of_range_is_rejected(): void
    {
        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), $this->basePayload([
                'kop_ukuran_judul' => '5',
            ]))
            ->assertSessionHasErrors('kop_ukuran_judul');

        $this->assertNull(KuaSetting::get('kop_ukuran_judul'));
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
