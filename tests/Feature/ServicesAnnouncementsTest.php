<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesAnnouncementsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public function test_public_announcement_search_filters_results(): void
    {
        Announcement::factory()->create(['title' => 'Libur Nasional', 'content' => 'KUA tutup.', 'active' => true]);
        Announcement::factory()->create(['title' => 'Jadwal Baru', 'content' => 'Jam layanan berubah.', 'active' => true]);

        $this->get(route('pengumuman.index', ['q' => 'Libur']))
            ->assertOk()
            ->assertSee('Libur Nasional')
            ->assertDontSee('Jadwal Baru');

        $this->get(route('pengumuman.index', ['q' => 'tidak-ada']))
            ->assertOk()
            ->assertSee('Tidak ada pengumuman yang cocok');
    }

    public function test_letter_type_seeder_creates_new_types(): void
    {
        $this->seed(\Database\Seeders\LetterTypeSeeder::class);

        foreach (['SPN', 'SKU', 'SPC', 'SUP', 'SIN', 'SP', 'SPD', 'SPA', 'SPM', 'SKN', 'PNL'] as $code) {
            $this->assertDatabaseHas('letter_types', ['code' => $code]);
        }

        foreach (['SPD', 'SPA', 'SKN', 'PNL'] as $code) {
            $this->assertDatabaseHas('letter_types', ['code' => $code, 'publik' => true]);
        }

        $this->assertDatabaseHas('letter_types', ['code' => 'SKU', 'publik' => false]);
    }

    public function test_announcement_seeder_creates_published_examples(): void
    {
        $this->seed(\Database\Seeders\AnnouncementSeeder::class);

        $this->assertSame(5, Announcement::count());
        $this->assertSame(5, Announcement::published()->count());
    }

    public function test_app_timezone_is_wib(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('+07:00', config('database.connections.mysql.timezone'));
    }

    public function test_landing_shows_center_menu_without_login(): void
    {
        Service::factory()->create(['name' => 'Pengajuan Surat Online', 'url' => '/permohonan', 'active' => true]);
        Announcement::factory()->create(['title' => 'Layanan Hari Ini', 'active' => true]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Beranda')
            ->assertSee('Layanan')
            ->assertSee('Pengumuman')
            ->assertSee('Pengajuan Surat Online')
            ->assertSee('Layanan Hari Ini')
            ->assertDontSee('Login Staf')
            ->assertDontSee('/login');
    }

    public function test_landing_hides_inactive_services_and_unpublished_announcements(): void
    {
        Service::factory()->create(['name' => 'Layanan Rahasia', 'active' => false]);
        Announcement::factory()->create([
            'title' => 'Draft Pengumuman',
            'active' => true,
            'published_at' => now()->addDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Layanan Rahasia')
            ->assertDontSee('Draft Pengumuman');
    }

    public function test_public_announcement_list_and_detail(): void
    {
        $announcement = Announcement::factory()->create([
            'title' => 'Penutupan Sementara',
            'content' => 'KUA tutup pada hari Jumat.',
            'active' => true,
        ]);

        $this->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('Penutupan Sementara');

        $this->get(route('pengumuman.show', $announcement))
            ->assertOk()
            ->assertSee('KUA tutup pada hari Jumat.');
    }

    public function test_scheduled_announcement_detail_returns_404(): void
    {
        $announcement = Announcement::factory()->create([
            'active' => true,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('pengumuman.show', $announcement))->assertNotFound();
        $this->get(route('pengumuman.index'))->assertDontSee($announcement->title);
    }

    public function test_submission_page_matches_landing_header_without_login(): void
    {
        $this->get(route('permohonan.create'))
            ->assertOk()
            ->assertSee('Beranda')
            ->assertSee('Layanan')
            ->assertSee('Pengumuman')
            ->assertDontSee('Login Petugas');
    }

    public function test_guest_cannot_access_admin_service_crud(): void
    {
        $this->get(route('services.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_create_and_edit_service(): void
    {
        $this->actingAs($this->user)
            ->post(route('services.store'), [
                'name' => 'Layanan Konsultasi',
                'description' => 'Konsultasi nikah',
                'url' => '/permohonan',
                'icon' => 'envelope',
                'sort_order' => 2,
                'active' => 1,
            ])
            ->assertRedirect(route('services.index'));

        $service = Service::where('name', 'Layanan Konsultasi')->first();
        $this->assertNotNull($service);
        $this->assertTrue($service->active);

        $this->actingAs($this->user)
            ->put(route('services.update', $service), [
                'name' => 'Layanan Konsultasi Online',
                'description' => 'Konsultasi nikah online',
                'url' => '/permohonan',
                'icon' => 'envelope',
                'sort_order' => 2,
                'active' => 0,
            ])
            ->assertRedirect(route('services.index'));

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Layanan Konsultasi Online',
            'active' => 0,
        ]);
    }

    public function test_service_icon_and_url_validated(): void
    {
        $this->actingAs($this->user)
            ->post(route('services.store'), [
                'name' => 'Ikon Salah',
                'icon' => 'not-exist',
                'url' => '#',
            ])
            ->assertSessionHasErrors(['icon', 'url']);

        $this->assertDatabaseMissing('services', ['name' => 'Ikon Salah']);
    }

    public function test_staff_can_delete_service(): void
    {
        $service = Service::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('services.destroy', $service))
            ->assertRedirect(route('services.index'));

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_staff_can_create_and_edit_announcement(): void
    {
        $this->actingAs($this->user)
            ->post(route('announcements.store'), [
                'title' => 'Libur Nasional',
                'content' => 'KUA tutup pada libur nasional.',
                'published_at' => now()->addDay()->format('Y-m-d\TH:i'),
                'active' => 1,
            ])
            ->assertRedirect(route('announcements.index'));

        $announcement = Announcement::where('title', 'Libur Nasional')->first();
        $this->assertNotNull($announcement);
        $this->assertNotNull($announcement->published_at);

        $this->actingAs($this->user)
            ->put(route('announcements.update', $announcement), [
                'title' => 'Libur Nasional Diperpanjang',
                'content' => 'KUA tutup hingga hari Senin.',
                'active' => 0,
            ])
            ->assertRedirect(route('announcements.index'));

        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'title' => 'Libur Nasional Diperpanjang',
            'active' => 0,
        ]);
    }

    public function test_staff_can_delete_announcement(): void
    {
        $announcement = Announcement::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('announcements.destroy', $announcement))
            ->assertRedirect(route('announcements.index'));

        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }

    public function test_announcement_requires_title_and_content(): void
    {
        $this->actingAs($this->user)
            ->post(route('announcements.store'), ['title' => '', 'content' => ''])
            ->assertSessionHasErrors(['title', 'content']);
    }
}
