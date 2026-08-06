<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function test_staff_can_create_page_with_auto_slug(): void
    {
        $this->actingAs($this->user)
            ->post(route('pages.store'), [
                'title' => 'Profil Kantor',
                'content' => 'Kantor Urusan Agama melayani masyarakat.',
                'active' => 1,
            ])
            ->assertRedirect(route('pages.index'));

        $this->assertDatabaseHas('pages', [
            'title' => 'Profil Kantor',
            'slug' => Str::slug('Profil Kantor'),
            'active' => 1,
        ]);
    }

    public function test_staff_can_update_page(): void
    {
        $page = Page::factory()->create(['slug' => 'profil-kantor']);

        $this->actingAs($this->user)
            ->put(route('pages.update', $page), [
                'title' => 'Profil KUA',
                'slug' => 'profil-kua',
                'content' => 'Konten baru.',
                'active' => 0,
            ])
            ->assertRedirect(route('pages.index'));

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Profil KUA',
            'slug' => 'profil-kua',
            'active' => 0,
        ]);
    }

    public function test_slug_gets_unique_suffix_when_collides(): void
    {
        $this->actingAs($this->user);
        Page::factory()->create(['title' => 'Tentang Kami', 'slug' => 'tentang-kami']);

        $this->post(route('pages.store'), [
            'title' => 'Tentang Kami',
            'content' => 'Halaman kedua.',
            'active' => 1,
        ])->assertRedirect(route('pages.index'));

        $this->assertDatabaseHas('pages', ['slug' => 'tentang-kami-2']);
    }

    public function test_staff_can_delete_page(): void
    {
        $page = Page::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('pages.destroy', $page))
            ->assertRedirect(route('pages.index'));

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    public function test_public_shows_active_page(): void
    {
        $page = Page::factory()->create([
            'title' => 'Sejarah KUA',
            'slug' => 'sejarah-kua',
            'content' => 'KUA berdiri sejak lama.',
            'active' => true,
        ]);

        $this->get(route('halaman.show', $page->slug))
            ->assertOk()
            ->assertSee('Sejarah KUA')
            ->assertSee('KUA berdiri sejak lama.');
    }

    public function test_public_returns_404_for_inactive_page(): void
    {
        $page = Page::factory()->create(['active' => false]);

        $this->get(route('halaman.show', $page->slug))->assertNotFound();
    }

    public function test_staff_can_upload_editor_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post(route('pages.gambar'), [
                'upload' => UploadedFile::fake()->image('isi.png', 800, 400),
            ])
            ->assertOk();

        $this->assertStringContainsString('/storage/pages/content/', (string) $response->json('url'));
        $this->assertNotEmpty(Storage::disk('public')->allFiles('pages/content'));
    }
}
