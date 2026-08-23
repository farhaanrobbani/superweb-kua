<?php

namespace Tests\Feature;

use App\Models\MarriageAnnouncement;
use App\Models\User;
use Database\Seeders\NavbarItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarriageAnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_shows_active_announcements(): void
    {
        $this->seed(NavbarItemSeeder::class);

        MarriageAnnouncement::factory()->create([
            'no_pendaftaran' => '2026/0123/PKN',
            'nama_pria' => 'Ahmad Fauzi',
            'bin_pria' => 'Muhammad Ali',
            'nama_wanita' => 'Siti Maryam',
            'binti_wanita' => 'Abdullah',
            'status_wali' => 'Ayah Kandung',
            'tanggal_akad' => now()->addDays(7),
        ]);

        $this->get('/pengumuman-nikah')
            ->assertOk()
            ->assertSee('Pengumuman Kehendak Nikah')
            ->assertSee('Ringkasan Jadwal')
            ->assertSee('1 pasangan')
            ->assertSee('Pasal 9 PMA No. 30 Tahun 2024')
            ->assertSee('2026/0123/PKN')
            ->assertSee('Ahmad Fauzi bin Muhammad Ali')
            ->assertSee('Siti Maryam binti Abdullah')
            ->assertSee('Ayah Kandung');
    }

    public function test_nama_lengkap_helpers_fall_back_without_bin_binti(): void
    {
        $item = MarriageAnnouncement::factory()->create([
            'nama_pria' => 'Budi',
            'bin_pria' => null,
            'nama_wanita' => 'Ani',
            'binti_wanita' => null,
        ]);

        $this->assertSame('Budi', $item->namaLengkapPria());
        $this->assertSame('Ani', $item->namaLengkapWanita());
    }

    public function test_public_page_hides_past_akad_dates(): void
    {
        $this->seed(NavbarItemSeeder::class);

        MarriageAnnouncement::factory()->create([
            'nama_pria' => 'Sudah Lewat',
            'tanggal_akad' => now()->subDays(3),
        ]);

        MarriageAnnouncement::factory()->create([
            'nama_pria' => 'Masih Aktif',
            'tanggal_akad' => now()->addDay(),
        ]);

        $this->get('/pengumuman-nikah')
            ->assertOk()
            ->assertDontSee('Sudah Lewat')
            ->assertSee('Masih Aktif');
    }

    public function test_public_page_hides_inactive_and_shows_empty_state(): void
    {
        $this->seed(NavbarItemSeeder::class);

        MarriageAnnouncement::factory()->create(['active' => false, 'nama_pria' => 'Nonaktif Rahasia']);

        $this->get('/pengumuman-nikah')
            ->assertOk()
            ->assertDontSee('Nonaktif Rahasia')
            ->assertSee('Belum ada pengumuman kehendak nikah');
    }

    public function test_operator_can_manage_marriage_announcements(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);

        $payload = [
            'no_pendaftaran' => '2026/0124/PKN',
            'nama_pria' => 'Ahmad Fauzi',
            'bin_pria' => 'Muhammad Ali',
            'asal_pria' => 'Putra dari Bpk. Ali',
            'alamat_pria' => 'Dusun Sukamaju, Kec. Ampelgading',
            'nama_wanita' => 'Siti Maryam',
            'binti_wanita' => 'Abdullah',
            'asal_wanita' => 'Putri dari Bpk. Abdullah',
            'alamat_wanita' => 'Dusun Sidodadi, Kec. Ampelgading',
            'tanggal_akad' => now()->addDays(10)->toDateString(),
            'tempat_nikah' => 'Masjid Nurul Iman',
            'status_wali' => 'Ayah Kandung',
            'active' => '1',
        ];

        $this->actingAs($operator)->get(route('marriage-announcements.index'))->assertOk();

        $this->actingAs($operator)->post(route('marriage-announcements.store'), $payload)
            ->assertRedirect(route('marriage-announcements.index'));

        $this->assertDatabaseHas('marriage_announcements', ['nama_pria' => 'Ahmad Fauzi', 'nama_wanita' => 'Siti Maryam']);

        $announcement = MarriageAnnouncement::firstOrFail();

        $this->actingAs($operator)->get(route('marriage-announcements.edit', $announcement))->assertOk();

        $this->actingAs($operator)->put(route('marriage-announcements.update', $announcement), array_merge($payload, ['tempat_nikah' => 'Balai Desa']))
            ->assertRedirect(route('marriage-announcements.index'));

        $this->assertDatabaseHas('marriage_announcements', ['id' => $announcement->id, 'tempat_nikah' => 'Balai Desa']);

        $this->actingAs($operator)->delete(route('marriage-announcements.destroy', $announcement))
            ->assertRedirect(route('marriage-announcements.index'));

        $this->assertDatabaseMissing('marriage_announcements', ['id' => $announcement->id]);
    }

    public function test_arsip_shows_past_and_hides_future(): void
    {
        $this->seed(NavbarItemSeeder::class);

        MarriageAnnouncement::factory()->create(['nama_pria' => 'Sudah Lewat', 'tanggal_akad' => now()->subDays(3)]);
        MarriageAnnouncement::factory()->create(['nama_pria' => 'Hari Ini', 'tanggal_akad' => today()]);
        MarriageAnnouncement::factory()->create(['nama_pria' => 'Masih Aktif', 'tanggal_akad' => now()->addDay()]);

        $this->get('/pengumuman-nikah/arsip')
            ->assertOk()
            ->assertSee('Arsip Pengumuman Kehendak Nikah')
            ->assertSee('Sudah Lewat')
            ->assertDontSee('Hari Ini')
            ->assertDontSee('Masih Aktif');
    }

    public function test_arsip_filter_by_date_range(): void
    {
        MarriageAnnouncement::factory()->create(['nama_pria' => 'Target Arsip', 'tanggal_akad' => now()->subDays(5)]);
        MarriageAnnouncement::factory()->create(['nama_pria' => 'Luar Rentang', 'tanggal_akad' => now()->subDays(30)]);

        $from = now()->subDays(6)->toDateString();
        $to = now()->subDays(4)->toDateString();

        $this->get("/pengumuman-nikah/arsip?dari={$from}&sampai={$to}")
            ->assertOk()
            ->assertSee('Target Arsip')
            ->assertDontSee('Luar Rentang');

        $this->get('/pengumuman-nikah/arsip?dari='.now()->addDay()->toDateString())
            ->assertOk()
            ->assertSee('Tidak ada arsip yang sesuai filter');
    }

    public function test_arsip_search_by_no_and_name(): void
    {
        MarriageAnnouncement::factory()->create(['no_pendaftaran' => '2026/0999/PKN', 'nama_pria' => 'Target Nama', 'tanggal_akad' => now()->subDays(2)]);
        MarriageAnnouncement::factory()->create(['nama_pria' => 'Lain Nama', 'tanggal_akad' => now()->subDays(2)]);

        $this->get('/pengumuman-nikah/arsip?q=Target+Nama')
            ->assertOk()
            ->assertSee('Target Nama')
            ->assertDontSee('Lain Nama');

        $this->get('/pengumuman-nikah/arsip?q=2026%2F0999%2FPKN')
            ->assertOk()
            ->assertSee('Target Nama');

        $this->get('/pengumuman-nikah/arsip?q=TIADA-XYZ')
            ->assertOk()
            ->assertSee('Tidak ada arsip yang sesuai filter');
    }

    public function test_index_shows_link_to_arsip(): void
    {
        $this->seed(NavbarItemSeeder::class);

        MarriageAnnouncement::factory()->create(['tanggal_akad' => now()->addDay()]);

        $this->get('/pengumuman-nikah')->assertOk()->assertSee('Lihat Arsip');
    }

    public function test_staff_cannot_access_admin_pages(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);

        $this->actingAs($staff)->get(route('marriage-announcements.index'))->assertForbidden();
    }

    public function test_store_validates_required_fields(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);

        $this->actingAs($operator)
            ->post(route('marriage-announcements.store'), [])
            ->assertSessionHasErrors(['nama_pria', 'nama_wanita', 'tanggal_akad']);
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->get(route('marriage-announcements.index'))->assertRedirect(route('login'));

        $guestTarget = MarriageAnnouncement::factory()->create();

        $this->delete(route('marriage-announcements.destroy', $guestTarget))
            ->assertRedirect(route('login'));
    }
}
