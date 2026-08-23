<?php

namespace App\Http\Controllers;

use App\Models\MarriageAnnouncement;
use App\Models\Page;
use Illuminate\View\View;

class MarriageAnnouncementPublicController extends Controller
{
    public function index(): View
    {
        return view('public.marriage-announcements.index', [
            'announcements' => MarriageAnnouncement::query()->aktif()->get(),
            'page' => $this->page(),
        ]);
    }

    private function page(): ?Page
    {
        try {
            return Page::active()->where('key', 'pengumuman-nikah')->first();
        } catch (\Throwable) {
            return null;
        }
    }
}
