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
            'nama_pria' => 'Ahmad Fauzi',
            'nama_wanita' => 'Siti Maryam',
            'tanggal_akad' => now()->addDays(7),
        ]);

        $this->get('/pengumuman-nikah')
            ->assertOk()
            ->assertSee('Pengumuman Kehendak Nikah')
            ->assertSee('Pasal 9 PMA No. 30 Tahun 2024')
            ->assertSee('Ahmad Fauzi')
            ->assertSee('Siti Maryam');
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
            'nama_pria' => 'Ahmad Fauzi',
            'asal_pria' => 'Putra dari Bpk. Ali',
            'nama_wanita' => 'Siti Maryam',
            'asal_wanita' => 'Putri dari Bpk. Abdullah',
            'tanggal_akad' => now()->addDays(10)->toDateString(),
            'tempat_nikah' => 'Masjid Nurul Iman',
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
