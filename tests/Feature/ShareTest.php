<?php

namespace Tests\Feature;

use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_page_contains_share_links_and_og_tags(): void
    {
        $announcement = Announcement::factory()->create([
            'title' => 'Penutupan Sementara',
            'content' => '<p>KUA tutup pada hari Jumat.</p>',
            'active' => true,
            'published_at' => null,
        ]);

        $shareUrl = route('pengumuman.show', $announcement);

        $this->get($shareUrl)
            ->assertOk()
            ->assertSee('https://wa.me/?text=', false)
            ->assertSee('https://www.facebook.com/sharer/sharer.php?u=', false)
            ->assertSee('Salin Tautan')
            ->assertSee('Bagikan')
            ->assertSee('<meta property="og:title" content="' . $announcement->title . '">', false)
            ->assertSee('<meta property="og:description" content="KUA tutup pada hari Jumat.">', false)
            ->assertSee('<meta property="og:type" content="article">', false)
            ->assertSee('<meta property="og:url" content="' . $shareUrl . '">', false);
    }

    public function test_show_page_includes_og_image_when_available(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('cover.jpg', 640, 480)
            ->store('announcements/covers', 'public');

        $announcement = Announcement::factory()->create([
            'title' => 'Penutupan Sementara',
            'content' => '<p>KUA tutup pada hari Jumat.</p>',
            'image' => $image,
            'active' => true,
            'published_at' => null,
        ]);

        $ogImage = Storage::disk('public')->url($image);

        $this->get(route('pengumuman.show', $announcement))
            ->assertOk()
            ->assertSee('<meta property="og:image" content="' . $ogImage . '">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
    }
}
