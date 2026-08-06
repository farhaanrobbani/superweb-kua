<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function cariAkta(): View|RedirectResponse
    {
        $service = Service::query()->where('url', '/cari-akta')->first();

        if (! $service?->embed_url) {
            return redirect()->route('welcome');
        }

        return view('public.layanan.show', compact('service'));
    }
}
