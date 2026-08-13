<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\KuaSetting;
use App\Models\LetterType;
use App\Models\NavbarItem;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function index(): View
    {
        return view('welcome', [
            'kua' => $this->kua(),
            'letterTypes' => $this->letterTypes(),
            'services' => $this->services(),
            'announcements' => $this->announcements(),
        ]);
    }

    private function kua(): array
    {
        $keys = ['instansi', 'alamat', 'telepon', 'email', 'kecamatan', 'kabupaten', 'kode_pos', 'kepala_nama', 'logo_path', 'hero_path', 'bg_path', 'hero_judul', 'hero_subjudul'];

        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->setting($key);
        }

        $values['logo_url'] = KuaSetting::logoUrl();
        $values['hero_url'] = KuaSetting::heroUrl();
        $values['bg_url'] = KuaSetting::backgroundUrl();

        return $values;
    }

    private function setting(string $key): ?string
    {
        try {
            return KuaSetting::get($key);
        } catch (\Throwable) {
            return null;
        }
    }

    private function letterTypes(): Collection
    {
        try {
            return LetterType::query()->where('active', true)->orderBy('name')->get(['name', 'description']);
        } catch (\Throwable) {
            return collect();
        }
    }

    private function services(): Collection
    {
        try {
            $layanan = NavbarItem::query()->where('key', 'layanan')->active()->first();

            return $layanan
                ? $layanan->children()->active()->ordered()->get()
                : collect();
        } catch (\Throwable) {
            return collect();
        }
    }

    private function announcements(): Collection
    {
        try {
            return Announcement::query()->published()->take(3)->get(['id', 'title', 'content', 'published_at', 'created_at', 'image']);
        } catch (\Throwable) {
            return collect();
        }
    }
}
