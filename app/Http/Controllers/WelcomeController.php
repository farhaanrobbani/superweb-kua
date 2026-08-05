<?php

namespace App\Http\Controllers;

use App\Models\KuaSetting;
use App\Models\LetterType;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function index(): View
    {
        return view('welcome', [
            'kua' => $this->kua(),
            'letterTypes' => $this->letterTypes(),
        ]);
    }

    private function kua(): array
    {
        $keys = ['instansi', 'alamat', 'telepon', 'email', 'kecamatan', 'kabupaten', 'kode_pos', 'kepala_nama', 'logo_path'];

        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->setting($key);
        }

        $values['logo_url'] = KuaSetting::logoUrl();

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
}
