<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function cariAkta(): View|RedirectResponse
    {
        try {
            $page = Page::active()->where('key', 'cari-akta')->first();
        } catch (\Throwable) {
            $page = null;
        }

        if (! $page?->embed_url) {
            return redirect()->route('welcome');
        }

        return view('public.layanan.show', compact('page'));
    }

    public function wakaf(): View
    {
        return $this->placeholder('wakaf', 'Wakaf');
    }

    public function keagamaan(): View
    {
        return $this->placeholder('keagamaan', 'Keagamaan');
    }

    private function placeholder(string $key, string $defaultTitle): View
    {
        try {
            $page = Page::active()->where('key', $key)->first();
        } catch (\Throwable) {
            $page = null;
        }

        if (! $page) {
            $page = (object) ['title' => $defaultTitle, 'description' => null, 'embed_url' => null];
        }

        return view('public.layanan.placeholder', compact('page'));
    }
}
