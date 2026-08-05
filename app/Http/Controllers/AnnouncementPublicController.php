<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\View\View;

class AnnouncementPublicController extends Controller
{
    public function index(): View
    {
        return view('public.announcements.index', [
            'announcements' => Announcement::published()->paginate(10),
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
}
