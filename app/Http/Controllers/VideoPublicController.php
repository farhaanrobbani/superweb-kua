<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoPublicController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $query = Video::query()->published();

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                   ->orWhere('content', 'like', "%{$q}%");
            });
        }

        return view('public.videos.index', [
            'q' => $q,
            'videos' => $query->paginate(9)->withQueryString(),
            'page' => $this->page(),
        ]);
    }

    public function showBySlug(string $slug): View
    {
        $video = Video::query()->published()->where('slug', $slug)->with('author')->firstOrFail();

        return view('public.videos.show', [
            'video' => $video,
            'related' => Video::query()->published()->where('id', '!=', $video->id)->inRandomOrder()->take(3)->get(),
        ]);
    }

    public function show(Video $video): View
    {
        return $this->showBySlug($video->slug);
    }

    private function page(?string $key = 'video'): ?Page
    {
        try {
            return Page::active()->where('key', $key)->first();
        } catch (\Throwable) {
            return null;
        }
    }
}
