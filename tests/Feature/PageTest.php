<?php

namespace Tests\Feature;

use App\Models\NavbarItem;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\NavbarItemSeeder;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->get(route('pages.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_update_pernikahan_page_title_and_description(): void
    {
        $this->actingAs($this->user)
            ->put(route('pages.update', ['key' => 'pernikahan']), [
                'title' => 'Nikah di KUA',
                'description' => 'Info layanan nikah terbaru.',
            ])
            ->assertRedirect(route('pages.index', ['tab' => 'pernikahan']));

        $this->assertDatabaseHas('pages', [
            'key' => 'pernikahan',
            'title' => 'Nikah di KUA',
            'description' => 'Info layanan nikah terbaru.',
            'active' => true,
        ]);
    }

    public function test_page_title_is_required(): void
    {
        $this->actingAs($this->user)
            ->put(route('pages.update', ['key' => 'pernikahan']), ['title' => '', 'description' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_staff_can_update_cari_akta_page_with_embed_url(): void
    {
        $this->actingAs($this->user)
            ->put(route('pages.update', ['key' => 'cari-akta']), [
                'title' => 'Cari Akta Nikah',
                'description' => 'Cek data akta nikah Anda.',
                'embed_url' => 'https://datastudio.google.com/embed/reporting/abc123/page/x',
            ])
            ->assertRedirect(route('pages.index', ['tab' => 'cari-akta']));

        $this->assertDatabaseHas('pages', [
            'key' => 'cari-akta',
            'title' => 'Cari Akta Nikah',
            'description' => 'Cek data akta nikah Anda.',
            'embed_url' => 'https://datastudio.google.com/embed/reporting/abc123/page/x',
            'active' => true,
        ]);
    }

    public function test_embed_url_must_be_valid_url(): void
    {
        $this->actingAs($this->user)
            ->put(route('pages.update', ['key' => 'cari-akta']), [
                'title' => 'Cari Akta',
                'embed_url' => 'bukan-url',
            ])
            ->assertSessionHasErrors('embed_url');
    }

    public function test_admin_pages_index_shows_tabs_matching_navbar_items(): void
    {
        $this->seed(NavbarItemSeeder::class);
        $this->seed(PageSeeder::class);

        $this->actingAs($this->user)
            ->get(route('pages.index'))
            ->assertOk()
            ->assertSee('Pernikahan')
            ->assertSee('Wakaf')
            ->assertSee('Keagamaan')
            ->assertSee('Pencarian Akta');
    }

    public function test_page_tabs_show_navbar_labels_not_page_titles(): void
    {
        $this->seed(NavbarItemSeeder::class);

        Page::where('key', 'pernikahan')->delete();
        Page::factory()->create([
            'key' => 'pernikahan',
            'title' => 'Halaman Nikah',
            'active' => true,
        ]);

        $this->actingAs($this->user)
            ->get(route('pages.index'))
            ->assertOk()
            ->assertSee('Pernikahan')
            ->assertDontSee('Halaman Nikah');
    }

    public function test_admin_pages_index_auto_creates_page_for_navbar_item(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $this->actingAs($this->user)->get(route('pages.index'))->assertOk();

        $this->assertDatabaseHas('pages', [
            'key' => 'wakaf',
            'title' => 'Wakaf',
            'active' => true,
        ]);
        $this->assertDatabaseHas('pages', [
            'key' => 'keagamaan',
            'title' => 'Keagamaan',
            'active' => true,
        ]);
    }

    public function test_admin_page_tabs_follow_navbar_item_removal(): void
    {
        $this->seed(NavbarItemSeeder::class);
        $wakaf = NavbarItem::where('key', 'wakaf')->firstOrFail();

        $this->actingAs($this->user)
            ->delete(route('navbar.destroy', $wakaf))
            ->assertRedirect(route('navbar.index'));

        $this->assertDatabaseMissing('pages', ['key' => 'wakaf']);

        $this->actingAs($this->user)
            ->get(route('pages.index'))
            ->assertOk()
            ->assertDontSee('Wakaf');
    }

    public function test_public_wakaf_page_is_empty_when_no_content(): void
    {
        $this->seed(PageSeeder::class);

        $this->get(route('layanan.wakaf'))
            ->assertOk()
            ->assertDontSee('<iframe');
    }

    public function test_public_keagamaan_page_is_empty_when_no_content(): void
    {
        $this->seed(PageSeeder::class);

        $this->get(route('layanan.keagamaan'))
            ->assertOk()
            ->assertDontSee('<iframe');
    }

    public function test_public_wakaf_page_renders_content_when_filled(): void
    {
        Page::factory()->create([
            'key' => 'wakaf',
            'title' => 'Wakaf',
            'description' => 'Info program wakaf KUA.',
            'embed_url' => 'https://datastudio.google.com/embed/reporting/wakaf123/page/x',
            'active' => true,
        ]);

        $this->get(route('layanan.wakaf'))
            ->assertOk()
            ->assertSee('Info program wakaf KUA.')
            ->assertSee('https://datastudio.google.com/embed/reporting/wakaf123/page/x');
    }

    public function test_public_cari_akta_page_uses_custom_title_description_and_embed(): void
    {
        Page::factory()->create([
            'key' => 'cari-akta',
            'title' => 'Cari Akta Nikah',
            'description' => 'Cek data akta nikah Anda.',
            'embed_url' => 'https://datastudio.google.com/embed/reporting/abc123/page/x',
            'active' => true,
        ]);

        $this->get(route('layanan.cari-akta'))
            ->assertOk()
            ->assertSee('Cari Akta Nikah')
            ->assertSee('Cek data akta nikah Anda.')
            ->assertSee('https://datastudio.google.com/embed/reporting/abc123/page/x');
    }

    public function test_public_pernikahan_page_uses_custom_title_and_description(): void
    {
        $this->seed(PageSeeder::class);

        $page = Page::where('key', 'pernikahan')->firstOrFail();
        $page->update([
            'title' => 'Nikah di KUA',
            'description' => 'Info layanan nikah terbaru.',
        ]);

        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Nikah di KUA')
            ->assertSee('Info layanan nikah terbaru.');
    }

    public function test_public_pernikahan_page_falls_back_to_defaults_when_table_empty(): void
    {
        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Layanan Pernikahan');
    }

    public function test_public_announcements_page_uses_custom_title_and_description(): void
    {
        Page::factory()->create([
            'key' => 'pengumuman',
            'title' => 'Info & Layanan',
            'description' => 'Pengumuman resmi KUA.',
            'active' => true,
        ]);

        $this->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('Info & Layanan')
            ->assertSee('Pengumuman resmi KUA.');
    }

    public function test_public_staff_page_uses_custom_title_and_description(): void
    {
        Page::factory()->create([
            'key' => 'pegawai',
            'title' => 'Profil Pegawai',
            'description' => 'Daftar pegawai KUA.',
            'active' => true,
        ]);

        $this->get(route('pegawai.index'))
            ->assertOk()
            ->assertSee('Profil Pegawai')
            ->assertSee('Daftar pegawai KUA.');
    }

    public function test_public_unduhan_page_uses_custom_title_and_description(): void
    {
        Page::factory()->create([
            'key' => 'unduhan',
            'title' => 'Berkas & Formulir',
            'description' => 'Unduh berkas resmi di sini.',
            'active' => true,
        ]);

        $this->get(route('unduhan.index'))
            ->assertOk()
            ->assertSee('Berkas & Formulir')
            ->assertSee('Unduh berkas resmi di sini.');
    }

    public function test_public_pages_fall_back_to_default_titles_when_empty(): void
    {
        $this->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('Pengumuman');

        $this->get(route('pegawai.index'))
            ->assertOk()
            ->assertSee('Struktur Organisasi');

        $this->get(route('unduhan.index'))
            ->assertOk()
            ->assertSee('Download Center');
    }

    public function test_inactive_page_is_not_used_in_public(): void
    {
        $this->seed(PageSeeder::class);
        Page::where('key', 'pernikahan')->firstOrFail()->update(['active' => false]);

        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Layanan Pernikahan');
    }
}
