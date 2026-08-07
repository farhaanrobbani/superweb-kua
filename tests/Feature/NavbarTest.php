<?php

namespace Tests\Feature;

use App\Models\NavbarItem;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\NavbarItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->assertSame(7, NavbarItem::count());
        $this->assertSame(4, NavbarItem::where('group', NavbarItem::GROUP_MAIN)->count());
        $this->assertSame(3, NavbarItem::where('group', NavbarItem::GROUP_TENTANG)->count());

        foreach (['beranda', 'layanan', 'pengumuman', 'tentang'] as $key) {
            $this->assertDatabaseHas('navbar_items', ['key' => $key]);
        }
    }

    public function test_guest_cannot_access_admin_navbar(): void
    {
        $this->get(route('navbar.index'))->assertRedirect(route('login'));
        $this->get(route('navbar.edit', NavbarItem::factory()->create()))->assertRedirect(route('login'));
    }

    public function test_staff_can_update_navbar_item(): void
    {
        $item = NavbarItem::factory()->create(['label' => 'Beranda', 'sort_order' => 1, 'active' => true]);

        $this->actingAs($this->user)
            ->put(route('navbar.update', $item), [
                'label' => 'Home',
                'sort_order' => 5,
                'active' => 0,
            ])
            ->assertRedirect(route('navbar.index'));

        $this->assertDatabaseHas('navbar_items', [
            'id' => $item->id,
            'label' => 'Home',
            'sort_order' => 5,
            'active' => 0,
        ]);
    }

    public function test_navbar_item_requires_label(): void
    {
        $item = NavbarItem::factory()->create();

        $this->actingAs($this->user)
            ->put(route('navbar.update', $item), ['label' => ''])
            ->assertSessionHasErrors('label');

        $this->assertDatabaseHas('navbar_items', ['id' => $item->id, 'label' => $item->label]);
    }

    public function test_public_header_uses_custom_labels_from_navbar_settings(): void
    {
        $this->seed(NavbarItemSeeder::class);
        Service::factory()->create(['name' => 'Pengajuan Surat Online', 'url' => '/permohonan', 'active' => true]);

        $beranda = NavbarItem::where('key', 'beranda')->firstOrFail();
        $beranda->update(['label' => 'Home']);

        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('Home')
            ->assertDontSee('Beranda');
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
        Service::factory()->create(['name' => 'Pengajuan Surat Online', 'url' => '/permohonan', 'active' => true]);

        $layanan = NavbarItem::where('key', 'layanan')->firstOrFail();
        $layanan->update(['active' => false]);

        $this->get(route('welcome'))
            ->assertOk()
            ->assertDontSee(':aria-expanded="layanan"');
    }

    public function test_main_item_order_respected_in_public_header(): void
    {
        $this->seed(NavbarItemSeeder::class);
        Service::factory()->create(['name' => 'Pengajuan Surat Online', 'url' => '/permohonan', 'active' => true]);

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
            ->assertSee('Pengumuman')
            ->assertSee('Tentang Kami')
            ->assertSee('Download Center');
    }
}
