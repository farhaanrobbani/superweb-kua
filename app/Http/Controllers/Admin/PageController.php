<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarriageService;
use App\Models\NavbarItem;
use App\Models\Page;
use App\Models\ReligiousService;
use App\Models\WakafService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = collect();

        foreach (NavbarItem::query()->active()->ordered()->get() as $item) {
            $page = Page::firstOrCreate(
                ['key' => $item->key],
                [
                    'title' => $item->label,
                    'active' => true,
                ]
            );
            $page->navbar_order = $item->sort_order;
            $page->navbar_label = $item->label;

            $pages->push($page);
        }

        $activeKey = $pages->contains(fn (Page $page) => $page->key === request('tab'))
            ? request('tab')
            : ($pages->first()?->key ?? 'pernikahan');

        $page = $pages->firstWhere('key', $activeKey)
            ?? Page::firstOrCreate(
                ['key' => 'pernikahan'],
                [
                    'title' => 'Layanan Pernikahan',
                    'description' => 'Pilih topik di bawah untuk melihat persyaratan, alur, dan prosedur layanan pernikahan di KUA. Beberapa layanan dapat diajukan secara online melalui tombol ajukan.',
                ]
            );

        return view('admin.pages.index', [
            'pages' => $pages,
            'page' => $page,
            'marriageServices' => MarriageService::ordered()->paginate(15),
            'religiousServices' => ReligiousService::ordered()->paginate(15),
            'wakafServices' => WakafService::ordered()->paginate(15),
        ]);
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'embed_url' => ['nullable', 'url', 'max:255'],
        ]);

        Page::updateOrCreate(
            ['key' => $key],
            [
                'title' => $data['title'],
                'description' => $data['description'],
                'embed_url' => $data['embed_url'] ?? null,
                'active' => true,
            ]
        );

        return redirect()->route('pages.index', ['tab' => $key])
            ->with('success', 'Halaman berhasil diperbarui.');
    }
}
