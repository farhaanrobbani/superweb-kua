<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Support\HtmlSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        return view('admin.announcements.index', [
            'announcements' => Announcement::orderByDesc('published_at')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Announcement::create($this->validatedWithImage($request));

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update($this->validatedWithImage($request, $announcement));

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
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
            'image' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'image_hapus' => ['sometimes', 'in:1'],
            'published_at' => ['nullable', 'date'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['content'] = HtmlSanitizer::sanitize($data['content']);

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
}
