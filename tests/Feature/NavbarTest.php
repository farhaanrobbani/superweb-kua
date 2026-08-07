<?php

namespace Tests\Feature;

use App\Models\NavbarItem;
use App\Models\User;
use Database\Seeders\NavbarItemSeeder;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public function test_seeder_creates_default_navbar_items(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $this->assertSame(8, NavbarItem::count());
        $this->assertSame(4, NavbarItem::root()->count());
        $this->assertSame(4, NavbarItem::whereNotNull('parent_id')->count());

        foreach (['beranda', 'layanan', 'pengumuman', 'tentang'] as $key) {
            $this->assertDatabaseHas('navbar_items', ['key' => $key]);
        }

        $this->assertDatabaseHas('navbar_items', ['key' => 'layanan', 'has_submenu' => 1]);
        $this->assertDatabaseHas('navbar_items', ['key' => 'tentang', 'has_submenu' => 1]);
        $this->assertDatabaseHas('navbar_items', ['key' => 'beranda', 'has_submenu' => 0]);

        $tentang = NavbarItem::where('key', 'tentang')->firstOrFail();
        $this->assertSame($tentang->id, NavbarItem::where('key', 'pegawai')->firstOrFail()->parent_id);
        $this->assertSame($tentang->id, NavbarItem::where('key', 'unduhan')->firstOrFail()->parent_id);
        $this->assertSame($tentang->id, NavbarItem::where('key', 'kritik-saran')->firstOrFail()->parent_id);

        $layanan = NavbarItem::where('key', 'layanan')->firstOrFail();
        $this->assertSame($layanan->id, NavbarItem::where('key', 'layanan-permohonan')->firstOrFail()->parent_id);
    }

    public function test_guest_cannot_access_admin_navbar(): void
    {
        $this->get(route('navbar.index'))->assertRedirect(route('login'));
        $this->get(route('navbar.edit', NavbarItem::factory()->create()))->assertRedirect(route('login'));
        $this->get(route('navbar.sub.create', NavbarItem::factory()->create()))->assertRedirect(route('login'));
    }

    public function test_staff_can_update_navbar_item(): void
    {
        $item = NavbarItem::factory()->create(['label' => 'Beranda', 'sort_order' => 1, 'active' => true]);

        $this->actingAs($this->user)
            ->put(route('navbar.update', $item), [
                'label' => 'Home',
                'description' => 'Halaman utama',
                'url' => '/',
                'icon' => 'home',
                'sort_order' => 5,
                'active' => 0,
                'has_submenu' => 1,
            ])
            ->assertRedirect(route('navbar.index'));

        $this->assertDatabaseHas('navbar_items', [
            'id' => $item->id,
            'label' => 'Home',
            'description' => 'Halaman utama',
            'url' => '/',
            'icon' => 'home',
            'sort_order' => 5,
            'active' => 0,
            'has_submenu' => 1,
        ]);
    }

    public function test_staff_can_create_main_item(): void
    {
        $this->actingAs($this->user)
            ->post(route('navbar.store'), [
                'label' => 'Unduhan',
                'url' => '/unduhan',
                'sort_order' => 5,
                'active' => 1,
                'has_submenu' => 0,
            ])
            ->assertRedirect(route('navbar.index'));

        $this->assertDatabaseHas('navbar_items', [
            'key' => 'unduhan',
            'label' => 'Unduhan',
            'url' => '/unduhan',
            'parent_id' => null,
        ]);
    }

    public function test_main_item_key_is_unique_slug(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $this->actingAs($this->user)
            ->post(route('navbar.store'), [
                'label' => 'Layanan',
                'url' => '/x',
                'active' => 1,
            ])
            ->assertRedirect(route('navbar.index'));

        $this->assertDatabaseHas('navbar_items', ['key' => 'layanan-2']);
    }

    public function test_staff_can_destroy_main_item_with_children(): void
    {
        $this->seed(NavbarItemSeeder::class);
        $tentang = NavbarItem::where('key', 'tentang')->firstOrFail();
        $childrenIds = $tentang->children()->pluck('id');

        $this->actingAs($this->user)
            ->delete(route('navbar.destroy', $tentang))
            ->assertRedirect(route('navbar.index'));

        $this->assertDatabaseMissing('navbar_items', ['id' => $tentang->id]);
        foreach ($childrenIds as $id) {
            $this->assertDatabaseMissing('navbar_items', ['id' => $id]);
        }
    }

    public function test_staff_can_create_sub_menu(): void
    {
        $this->seed(NavbarItemSeeder::class);
        $layanan = NavbarItem::where('key', 'layanan')->firstOrFail();

        $this->actingAs($this->user)
            ->post(route('navbar.sub.store', $layanan), [
                'label' => 'Layanan Baru',
                'url' => '/layanan-baru',
                'sort_order' => 9,
                'active' => 1,
            ])
            ->assertRedirect(route('navbar.index'));

        $this->assertDatabaseHas('navbar_items', [
            'label' => 'Layanan Baru',
            'url' => '/layanan-baru',
            'parent_id' => $layanan->id,
            'has_submenu' => 0,
        ]);
    }

    public function test_navbar_item_rejects_invalid_icon(): void
    {
        $item = NavbarItem::factory()->create();

        $this->actingAs($this->user)
            ->put(route('navbar.update', $item), ['label' => 'Ok', 'icon' => 'bukan-ikon'])
            ->assertSessionHasErrors('icon');
    }

    public function test_edit_page_titles_match_item_type(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $this->actingAs($this->user)
            ->get(route('navbar.edit', NavbarItem::where('key', 'beranda')->firstOrFail()))
            ->assertOk()
            ->assertSee('Edit Item Navbar');

        $this->actingAs($this->user)
            ->get(route('navbar.sub.edit', NavbarItem::where('key', 'unduhan')->firstOrFail()))
            ->assertOk()
            ->assertSee('Edit Sub Menu Tentang')
            ->assertDontSee('Edit Item Navbar');

        $this->actingAs($this->user)
            ->get(route('navbar.sub.create', NavbarItem::where('key', 'layanan')->firstOrFail()))
            ->assertOk()
            ->assertSee('Tambah Sub Menu Layanan');
    }

    public function test_navbar_item_requires_label(): void
    {
        $item = NavbarItem::factory()->create();

        $this->actingAs($this->user)
            ->put(route('navbar.update', $item), ['label' => ''])
            ->assertSessionHasErrors('label');

        $this->assertDatabaseHas('navbar_items', ['id' => $item->id, 'label' => $item->label]);
    }

    public function test_index_shows_add_button_on_main_and_submenu_tables(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $this->actingAs($this->user)
            ->get(route('navbar.index'))
            ->assertOk()
            ->assertSee('+ Tambah Item Navbar')
            ->assertSee('+ Tambah Sub Menu Layanan')
            ->assertSee('+ Tambah Sub Menu Tentang Kami');
    }

    public function test_public_header_uses_custom_labels_from_navbar_settings(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $beranda = NavbarItem::where('key', 'beranda')->firstOrFail();
        $beranda->update(['label' => 'Home']);

        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('Home')
            ->assertDontSee('Beranda');
    }

    public function test_landing_service_cards_show_navbar_item_labels(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $permohonan = NavbarItem::where('key', 'layanan-permohonan')->firstOrFail();
        $permohonan->update(['label' => 'Ajukan Surat Online']);

        $response = $this->get(route('welcome'));
        $response->assertOk();

        $dom = new DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new DOMXPath($dom);

        $node = $xpath->query('//a[contains(@href, "/permohonan")]//h3')->item(0);

        $this->assertNotNull($node, 'Kartu layanan /permohonan tidak ditemukan.');
        $this->assertSame('Ajukan Surat Online', trim($node->textContent), 'Kartu layanan tidak menampilkan label.');
    }

    public function test_inactive_main_item_is_hidden_from_public_header(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $pengumuman = NavbarItem::where('key', 'pengumuman')->firstOrFail();
        $pengumuman->update(['active' => false]);

        $this->get(route('welcome'))
            ->assertOk()
            ->assertDontSee('>Pengumuman</a>');
    }

    public function test_inactive_layanan_item_hides_dropdown(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $layanan = NavbarItem::where('key', 'layanan')->firstOrFail();
        $layanan->update(['active' => false]);

        $html = $this->get(route('welcome'))->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'x-data="{ open: false }"'));
    }

    public function test_layanan_without_submenu_becomes_direct_link(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $layanan = NavbarItem::where('key', 'layanan')->firstOrFail();
        $layanan->update(['has_submenu' => false]);

        $html = $this->get(route('welcome'))->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'x-data="{ open: false }"'));
        $this->assertStringContainsString('>Layanan</a>', $html);
    }

    public function test_tentang_without_submenu_becomes_direct_link(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $tentang = NavbarItem::where('key', 'tentang')->firstOrFail();
        $tentang->update(['has_submenu' => false]);

        $html = $this->get(route('welcome'))->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'x-data="{ open: false }"'));
        $this->assertStringContainsString('>Tentang Kami</a>', $html);
    }

    public function test_main_item_with_submenu_but_no_children_is_hidden_in_public(): void
    {
        NavbarItem::factory()->create([
            'label' => 'Layanan Khusus',
            'url' => '/khusus',
            'has_submenu' => true,
            'active' => true,
        ]);

        $this->get(route('welcome'))
            ->assertOk()
            ->assertDontSee('Layanan Khusus');
    }

    public function test_main_item_order_respected_in_public_header(): void
    {
        $this->seed(NavbarItemSeeder::class);

        NavbarItem::where('key', 'pengumuman')->firstOrFail()->update(['sort_order' => 0]);
        NavbarItem::where('key', 'beranda')->firstOrFail()->update(['sort_order' => 9]);

        $html = $this->get(route('welcome'))->assertOk()->getContent();

        $pengumumanPos = strpos($html, '>Pengumuman</a>');
        $berandaPos = strpos($html, '>Beranda</a>');

        $this->assertNotFalse($pengumumanPos);
        $this->assertNotFalse($berandaPos);
        $this->assertLessThan($berandaPos, $pengumumanPos, 'Urutan item navbar tidak dihormati.');
    }

    public function test_inactive_tentang_sub_item_is_hidden_from_dropdown(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $unduhan = NavbarItem::where('key', 'unduhan')->firstOrFail();
        $unduhan->update(['active' => false]);

        $this->get(route('welcome'))
            ->assertOk()
            ->assertDontSee('Download Center');
    }

    public function test_tentang_sub_item_label_customizable(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $unduhan = NavbarItem::where('key', 'unduhan')->firstOrFail();
        $unduhan->update(['label' => 'File Unduhan']);

        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('File Unduhan')
            ->assertDontSee('Download Center');
    }

    public function test_public_header_falls_back_to_defaults_when_table_empty(): void
    {
        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('Beranda')
            ->assertSee('Layanan')
            ->assertSee('Pengajuan Surat Online')
            ->assertSee('Pengumuman')
            ->assertSee('Tentang Kami')
            ->assertSee('Download Center');
    }

    public function test_public_header_falls_back_to_defaults_when_table_missing(): void
    {
        Schema::dropIfExists('navbar_items');

        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('Beranda')
            ->assertSee('Layanan')
            ->assertSee('Pengajuan Surat Online')
            ->assertSee('Pengumuman')
            ->assertSee('Tentang Kami')
            ->assertSee('Download Center');
    }

    public function test_sub_menu_dropdown_shows_on_non_welcome_page(): void
    {
        $this->seed(NavbarItemSeeder::class);

        $this->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('Pengajuan Surat Online');
    }
}
