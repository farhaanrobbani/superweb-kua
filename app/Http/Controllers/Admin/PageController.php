<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\HtmlSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::orderBy('title')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Page::create($this->validatedWithContent($request));

        return redirect()->route('pages.index')
            ->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validatedWithContent($request, $page));

        return redirect()->route('pages.index')
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => ['required', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:3072'],
        ]);

        $path = $request->file('upload')->store('pages/content', 'public');

        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }

    private function validatedWithContent(Request $request, ?Page $page = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'content' => ['required', 'string', 'max:100000'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['content'] = HtmlSanitizer::sanitize($data['content']);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? Str::slug($data['title']), $page);

        return $data;
    }

    private function uniqueSlug(string $slug, ?Page $page): string
    {
        $slug = trim($slug) !== '' ? $slug : 'halaman';
        $base = $slug;
        $i = 2;

        while (Page::where('slug', $slug)->where('id', '!=', $page?->id)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
