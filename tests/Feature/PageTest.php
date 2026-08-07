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
            ->put(route('pages.pernikahan.update'), [
                'title' => 'Nikah di KUA',
                'description' => 'Info layanan nikah terbaru.',
            ])
            ->assertRedirect(route('pages.index'));

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
            ->put(route('pages.pernikahan.update'), ['title' => '', 'description' => null])
            ->assertSessionHasErrors('title');
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
