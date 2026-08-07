<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementPublicController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q'));

        $announcements = Announcement::published()
            ->when($query !== '', function ($builder) use ($query) {
                return $builder->where(function ($builder) use ($query) {
                    return $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('public.announcements.index', [
            'announcements' => $announcements,
            'q' => $query,
            'page' => $this->page('pengumuman'),
        ]);
    }

    public function show(Announcement $announcement): View
    {
        abort_unless(
            $announcement->active && ($announcement->published_at === null || $announcement->published_at->lte(now())),
            404
        );

        return view('public.announcements.show', compact('announcement'));
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
