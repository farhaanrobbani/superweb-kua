<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::orderBy('key')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        Page::create($data);

        return redirect()->route('pages.index')
            ->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $data = $this->validatedData($request, $page);

        $page->update($data);

        return redirect()->route('pages.index')
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Page $page = null): array
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'alpha_dash', 'max:100', 'unique:pages,key'.($page ? ','.$page->id : '')],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string', 'max:100000'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['content'] = HtmlSanitizer::sanitize($data['content'] ?? null);
        $data['active'] = $request->boolean('active');

        return $data;
    }
}