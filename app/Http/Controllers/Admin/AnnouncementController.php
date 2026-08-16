<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AnnouncementCategory;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Support\HtmlSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        return view('admin.announcements.index', [
            'announcements' => Announcement::with('author')->orderByDesc('published_at')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.announcements.create', [
            'categories' => AnnouncementCategory::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedWithImage($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug'), $data['title']);
        $data['author_id'] = $request->user()->id;

        Announcement::create($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Post berhasil ditambahkan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', [
            'announcement' => $announcement,
            'categories' => AnnouncementCategory::cases(),
        ]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $this->validatedWithImage($request, $announcement);
        $data['slug'] = $this->uniqueSlug($request->input('slug'), $data['title'], $announcement->id);

        $announcement->update($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Post berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Post berhasil dihapus.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => ['required', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:3072'],
        ]);

        $path = $request->file('upload')->store('announcements/content', 'public');

        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }

    private function validatedWithImage(Request $request, ?Announcement $announcement = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string', 'max:100000'],
            'category' => ['sometimes', 'string', Rule::in(array_column(AnnouncementCategory::cases(), 'value'))],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'image' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'image_hapus' => ['sometimes', 'in:1'],
            'published_at' => ['nullable', 'date'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['content'] = HtmlSanitizer::sanitize($data['content']);
        $data['excerpt'] = trim((string) ($data['excerpt'] ?? '')) !== ''
            ? trim($data['excerpt'])
            : Str::limit(strip_tags($data['content']), 160);

        if ($request->hasFile('image')) {
            if ($announcement?->image && Storage::disk('public')->exists($announcement->image)) {
                Storage::disk('public')->delete($announcement->image);
            }

            $data['image'] = $request->file('image')->store('announcements/covers', 'public');
        } elseif ($request->boolean('image_hapus')) {
            if ($announcement?->image && Storage::disk('public')->exists($announcement->image)) {
                Storage::disk('public')->delete($announcement->image);
            }

            $data['image'] = null;
        }

        return $data;
    }

    private function uniqueSlug(?string $input, string $title, ?int $ignoreId = null): string
    {
        $base = trim((string) $input) !== ''
            ? Str::slug($input)
            : Str::slug($title);

        $base = $base === '' ? 'pengumuman' : $base;

        $candidate = $base;
        $i = 2;
        while (Announcement::query()->where('slug', $candidate)->where('id', '!=', $ignoreId)->exists()) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }
}
