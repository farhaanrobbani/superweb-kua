<?php

namespace App\Http\Controllers;

use App\Enums\AnnouncementCategory;
use App\Models\Announcement;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementPublicController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q'));
        $category = $request->query('category');

        $announcements = Announcement::with('author')
            ->published()
            ->when(
                $category !== null && in_array($category, array_column(AnnouncementCategory::cases(), 'value')),
                fn ($builder) => $builder->where('category', $category)
            )
            ->when($query !== '', function ($builder) use ($query) {
                return $builder->where(function ($builder) use ($query) {
                    return $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                });
            })
            ->paginate(9)
            ->withQueryString();

        return view('public.announcements.index', [
            'announcements' => $announcements,
            'q' => $query,
            'category' => $category,
            'categories' => AnnouncementCategory::cases(),
            'page' => $this->page('pengumuman'),
        ]);
    }

    public function show(Announcement $announcement): View
    {
        return $this->showBySlug($announcement->slug);
    }

    public function showBySlug(string $slug): View
    {
        $announcement = Announcement::with('author')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Announcement::with('author')
            ->published()
            ->where('id', '!=', $announcement->id)
            ->when($announcement->category, fn ($builder) => $builder->where('category', $announcement->category))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.announcements.show', [
            'announcement' => $announcement,
            'related' => $related,
        ]);
    }

    private function page(string $key): ?Page
    {
        try {
            return Page::query()->where('key', $key)->active()->first();
        } catch (\Throwable) {
            return null;
        }
    }
}
