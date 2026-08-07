<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarriageService;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $page = Page::firstOrCreate(
            ['key' => 'pernikahan'],
            [
                'title' => 'Layanan Pernikahan',
                'description' => 'Pilih topik di bawah untuk melihat persyaratan, alur, dan prosedur layanan pernikahan di KUA. Beberapa layanan dapat diajukan secara online melalui tombol ajukan.',
            ]
        );

        return view('admin.pages.index', [
            'page' => $page,
            'marriageServices' => MarriageService::ordered()->paginate(15),
        ]);
    }

    public function updatePernikahan(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Page::updateOrCreate(
            ['key' => 'pernikahan'],
            [
                'title' => $data['title'],
                'description' => $data['description'],
                'active' => true,
            ]
        );

        return redirect()->route('pages.index')
            ->with('success', 'Halaman Pernikahan berhasil diperbarui.');
    }
}
