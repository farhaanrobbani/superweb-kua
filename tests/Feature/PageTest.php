<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
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

    public function test_admin_pages_index_shows_all_page_tabs(): void
    {
        $this->seed(PageSeeder::class);

        $this->actingAs($this->user)
            ->get(route('pages.index'))
            ->assertOk()
            ->assertSee('Pernikahan')
            ->assertSee('Pencarian Akta');
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

    public function test_inactive_page_is_not_used_in_public(): void
    {
        $this->seed(PageSeeder::class);
        Page::where('key', 'pernikahan')->firstOrFail()->update(['active' => false]);

        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Layanan Pernikahan');
    }
}
