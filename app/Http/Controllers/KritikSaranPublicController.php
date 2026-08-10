<?php

namespace App\Http\Controllers;

use App\Models\KritikSaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KritikSaranPublicController extends Controller
{
    public function create(): View
    {
        return view('public.kritik-saran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kontak' => ['nullable', 'string', 'max:150'],
            'kategori' => ['nullable', 'string', 'max:50'],
            'isi' => ['required', 'string', 'max:5000'],
        ]);

        KritikSaran::create($validated);

        return back()->with('success', 'Terima kasih! Kritik dan saran Anda telah kami terima.');
    }
}
