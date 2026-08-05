<?php

namespace App\Http\Controllers;

use App\Models\KuaSetting;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function index(): View
    {
        return view('welcome', [
            'kua' => [
                'instansi' => $this->setting('instansi'),
                'alamat' => $this->setting('alamat'),
                'telepon' => $this->setting('telepon'),
            ],
        ]);
    }

    private function setting(string $key): ?string
    {
        try {
            return KuaSetting::get($key);
        } catch (\Throwable) {
            return null;
        }
    }
}
