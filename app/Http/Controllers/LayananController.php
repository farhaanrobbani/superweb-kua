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
}
