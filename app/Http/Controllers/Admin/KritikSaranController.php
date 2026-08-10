<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KritikSaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KritikSaranController extends Controller
{
    public function index(): View
    {
        return view('admin.kritik-saran.index', [
            'feedbacks' => KritikSaran::latest()->paginate(15),
        ]);
    }

    public function show(KritikSaran $kritikSaran): View
    {
        return view('admin.kritik-saran.show', [
            'feedback' => $kritikSaran,
        ]);
    }

    public function destroy(KritikSaran $kritikSaran): RedirectResponse
    {
        $kritikSaran->delete();

        return redirect()->route('kritik-saran.index')
            ->with('success', 'Kritik/saran berhasil dihapus.');
    }
}
