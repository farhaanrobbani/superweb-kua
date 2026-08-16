<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\NavbarItem;
use App\Models\Page;
use Database\Seeders\NavbarItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomNavbarUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(NavbarItemSeeder::class);
    }

    private function setUrl(string $key, string $url): void
    {
        NavbarItem::where('key', $key)->firstOrFail()->update(['url' => $url]);
    }

    public function test_custom_pengumuman_url_renders_index_and_detail(): void
    {
        $this->setUrl('pengumuman', '/berita');

        $announcement = Announcement::factory()->create(['title' => 'Kabar Terbaru']);

        $this->get('/berita')
            ->assertOk()
            ->assertSee('Kabar Terbaru');

        $this->get('/berita/'.$announcement->slug)
            ->assertOk()
            ->assertSee($announcement->title);
    }

    public function test_custom_pengumuman_url_supports_search_and_category_query(): void
    {
        $this->setUrl('pengumuman', '/berita');

        Announcement::factory()->create(['title' => 'Libur Nasional']);
        Announcement::factory()->create(['title' => 'Jadwal Baru']);

        $this->get('/berita?q=Libur')
            ->assertOk()
            ->assertSee('Libur Nasional')
            ->assertDontSee('Jadwal Baru');

        $this->get('/berita?category=news')
            ->assertOk();
    }

    public function test_legacy_pengumuman_url_redirects_301_to_canonical(): void
    {
        $this->setUrl('pengumuman', '/berita');

        $announcement = Announcement::factory()->create();

        $this->get('/pengumuman')->assertStatus(301)->assertLocation('/berita');
        $this->get('/pengumuman?category=news')->assertStatus(301)->assertLocation('/berita?category=news');
        $this->get('/pengumuman/'.$announcement->slug)->assertStatus(301)->assertLocation('/berita/'.$announcement->slug);
    }

    public function test_legacy_pengumuman_url_not_redirected_when_url_unchanged(): void
    {
        $this->get('/pengumuman')->assertOk();
    }

    public function test_custom_url_other_pages_resolve_via_fallback(): void
    {
        $this->setUrl('pernikahan', '/layanan-nikah');
        $this->get('/layanan-nikah')->assertOk();

        $this->setUrl('layanan-permohonan', '/pengajuan');
        $this->get('/pengajuan')->assertOk();
    }

    public function test_functional_endpoints_are_not_redirected(): void
    {
        $this->setUrl('layanan-permohonan', '/pengajuan');

        $this->post('/permohonan', [])->assertStatus(302);
        $this->get('/pengajuan')->assertOk();
        $this->post('/pengajuan', [])->assertNotFound();
    }

    public function test_unknown_url_returns_404(): void
    {
        $this->get('/halaman-tidak-ada')->assertNotFound();
    }

    public function test_tab_title_follows_navbar_label(): void
    {
        $this->setUrl('pengumuman', '/cobaaa');
        NavbarItem::where('key', 'pengumuman')->firstOrFail()->update(['label' => 'Berita']);

        $this->get('/cobaaa')
            ->assertOk()
            ->assertSee('<title>Berita — Surat Digital KUA</title>', false);
    }

    public function test_tab_title_falls_back_to_page_title(): void
    {
        NavbarItem::where('key', 'pengumuman')->delete();
        Page::create(['key' => 'pengumuman', 'title' => 'Berita KUA', 'active' => true]);

        $this->get('/pengumuman')
            ->assertOk()
            ->assertSee('<title>Berita KUA — Surat Digital KUA</title>', false);
    }
}
