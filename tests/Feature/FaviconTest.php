<?php

namespace Tests\Feature;

use App\Models\KuaSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FaviconTest extends TestCase
{
    use RefreshDatabase;

    public function test_favicon_falls_back_to_default_when_no_logo(): void
    {
        $this->get('/favicon.ico')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/x-empty');
    }

    public function test_favicon_serves_uploaded_logo(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo.png', 64, 64)->store('logos', 'public');
        KuaSetting::set('logo_path', $path);

        $response = $this->get('/favicon.ico');

        $response->assertOk()->assertHeader('Content-Type', 'image/png');

        $this->assertSame(
            file_get_contents(Storage::disk('public')->path($path)),
            $response->streamedContent()
        );
    }

    public function test_favicon_falls_back_after_logo_deleted(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo.png', 64, 64)->store('logos', 'public');
        KuaSetting::set('logo_path', $path);

        Storage::disk('public')->delete($path);
        KuaSetting::set('logo_path', '');

        $this->get('/favicon.ico')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/x-empty');
    }

    public function test_landing_shows_favicon_link(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo.png', 64, 64)->store('logos', 'public');
        KuaSetting::set('logo_path', $path);

        $this->get('/')
            ->assertOk()
            ->assertSee('rel="icon"', false)
            ->assertSee('storage/logos/', false);
    }
}
