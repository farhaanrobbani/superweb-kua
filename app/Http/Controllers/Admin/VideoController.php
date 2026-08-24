<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        return view('admin.videos.index', [
            'videos' => Video::with('author')->orderByDesc('published_at')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.videos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug'), $data['title']);
        $data['author_id'] = $request->user()->id;

        Video::create($data);

        return redirect()->route('videos.index')
            ->with('success', 'Video berhasil ditambahkan.');
    }

    public function edit(Video $video): View
    {
        return view('admin.videos.edit', [
            'video' => $video,
        ]);
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $data = $this->validated($request, $video);
        $data['slug'] = $this->uniqueSlug($request->input('slug'), $data['title'], $video->id);

        $video->update($data);

        return redirect()->route('videos.index')
            ->with('success', 'Video berhasil diperbarui.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()->route('videos.index')
            ->with('success', 'Video berhasil dihapus.');
    }

    private function validated(Request $request, ?Video $video = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220'],
            'video_url' => ['required', 'url', 'max:2000'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string', 'max:100000'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'thumbnail_hapus' => ['nullable', 'in:1'],
            'published_at' => ['nullable', 'date'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['title'] = trim($data['title']);
        $data['excerpt'] = isset($data['excerpt']) ? trim($data['excerpt']) : null;
        $data['content'] = isset($data['content']) && trim($data['content']) !== ''
            ? HtmlSanitizer::sanitize($data['content'])
            : null;
        $data['published_at'] = $data['published_at'] ?? null;
        $data['active'] = $request->boolean('active');
        $data['slug'] = $data['slug'] ?? null;

        $existingThumbnail = $video?->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if ($existingThumbnail && Storage::disk('public')->exists($existingThumbnail)) {
                Storage::disk('public')->delete($existingThumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        } elseif ($request->input('thumbnail_hapus') === '1') {
            if ($existingThumbnail && Storage::disk('public')->exists($existingThumbnail)) {
                Storage::disk('public')->delete($existingThumbnail);
            }
            $data['thumbnail'] = null;
        } else {
            $data['thumbnail'] = $existingThumbnail;
        }

        unset($data['thumbnail_hapus']);

        return $data;
    }

    private function uniqueSlug(?string $inputSlug, string $title, ?int $ignoreId = null): string
    {
        $base = $inputSlug ? Str::slug($inputSlug) : Str::slug($title);
        $base = $base === '' ? 'video' : $base;
        $slug = $base;
        $i = 2;
        while (Video::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
