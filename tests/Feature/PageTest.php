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
        $this->get(route('pages.create'))->assertRedirect(route('login'));
        $this->get(route('pages.edit', Page::factory()->create()))->assertRedirect(route('login'));
    }

    public function test_staff_can_create_page(): void
    {
        $this->actingAs($this->user)
            ->post(route('pages.store'), [
                'key' => 'visi-misi',
                'title' => 'Visi & Misi',
                'description' => 'Visi dan misi kantor.',
                'active' => 1,
            ])
            ->assertRedirect(route('pages.index'));

        $this->assertDatabaseHas('pages', [
            'key' => 'visi-misi',
            'title' => 'Visi & Misi',
            'description' => 'Visi dan misi kantor.',
            'active' => 1,
        ]);
    }

    public function test_page_key_must_be_unique(): void
    {
        Page::factory()->create(['key' => 'pernikahan']);

        $this->actingAs($this->user)
            ->post(route('pages.store'), ['key' => 'pernikahan', 'title' => 'Duplikat'])
            ->assertSessionHasErrors('key');
    }

    public function test_page_requires_key_and_title(): void
    {
        $this->actingAs($this->user)
            ->post(route('pages.store'), ['key' => '', 'title' => ''])
            ->assertSessionHasErrors(['key', 'title']);
    }

    public function test_staff_can_update_page(): void
    {
        $page = Page::factory()->create(['key' => 'pernikahan']);

        $this->actingAs($this->user)
            ->put(route('pages.update', $page), [
                'key' => 'pernikahan',
                'title' => 'Layanan Nikah',
                'description' => 'Deskripsi baru.',
                'active' => 0,
            ])
            ->assertRedirect(route('pages.index'));

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Layanan Nikah',
            'description' => 'Deskripsi baru.',
            'active' => 0,
        ]);
    }

    public function test_staff_can_destroy_page(): void
    {
        $page = Page::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('pages.destroy', $page))
            ->assertRedirect(route('pages.index'));

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    public function test_content_is_sanitized_on_save(): void
    {
        $this->actingAs($this->user)
            ->post(route('pages.store'), [
                'key' => 'khusus',
                'title' => 'Halaman',
                'content' => '<script>alert(1)</script><p onmouseover="x()">Halo <strong>dunia</strong></p>',
            ])
            ->assertRedirect(route('pages.index'));

        $saved = Page::where('key', 'khusus')->firstOrFail();
        $this->assertStringNotContainsString('<script', $saved->content);
        $this->assertStringNotContainsString('onmouseover', $saved->content);
        $this->assertStringContainsString('<strong>dunia</strong>', $saved->content);
    }

    public function test_public_pernikahan_page_uses_custom_title_description_and_content(): void
    {
        $this->seed(PageSeeder::class);

        $page = Page::where('key', 'pernikahan')->firstOrFail();
        $page->update([
            'title' => 'Nikah di KUA',
            'description' => 'Info layanan nikah terbaru.',
            'content' => '<h2>Jam Layanan Nikah</h2><p>Senin–Jumat 08.00–15.00.</p>',
        ]);

        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Nikah di KUA')
            ->assertSee('Info layanan nikah terbaru.')
            ->assertSee('Jam Layanan Nikah');
    }

    public function test_public_unduhan_page_uses_custom_title(): void
    {
        $this->seed(PageSeeder::class);
        Page::where('key', 'unduhan')->firstOrFail()->update(['title' => 'Arsip Berkas']);

        $this->get(route('unduhan.index'))
            ->assertOk()
            ->assertSee('Arsip Berkas');
    }

    public function test_public_staff_page_uses_custom_title(): void
    {
        $this->seed(PageSeeder::class);
        Page::where('key', 'daftar-pegawai')->firstOrFail()->update(['title' => 'SDM KUA']);

        $this->get(route('pegawai.index'))
            ->assertOk()
            ->assertSee('SDM KUA')
            ->assertDontSee('Struktur Organisasi');
    }

    public function test_public_kritik_saran_page_uses_custom_description(): void
    {
        $this->seed(PageSeeder::class);
        Page::where('key', 'kritik-saran')->firstOrFail()->update(['description' => 'Sampaikan masukan Anda di sini.']);

        $this->get(route('kritik-saran.create'))
            ->assertOk()
            ->assertSee('Sampaikan masukan Anda di sini.');
    }

    public function test_public_page_falls_back_to_defaults_when_table_empty(): void
    {
        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Layanan Pernikahan');

        $this->get(route('unduhan.index'))
            ->assertOk()
            ->assertSee('Download Center');

        $this->get(route('kritik-saran.create'))
            ->assertOk()
            ->assertSee('Kritik & Saran');
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
