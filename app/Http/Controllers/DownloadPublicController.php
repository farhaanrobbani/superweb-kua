<?php

namespace App\Http\Controllers;

use App\Models\DownloadItem;
use App\Models\Page;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadPublicController extends Controller
{
    public function index(): View
    {
        $items = DownloadItem::published()->get();

        $categories = $items->groupBy(fn (DownloadItem $item) => trim((string) $item->category) !== '' ? $item->category : 'Lainnya');

        try {
            $page = Page::query()->where('key', 'unduhan')->active()->first();
        } catch (\Throwable) {
            $page = null;
        }

        return view('public.unduhan.index', [
            'categories' => $categories,
            'total' => $items->count(),
            'page' => $page,
        ]);
    }

    public function download(DownloadItem $downloadItem): StreamedResponse
    {
        abort_unless($downloadItem->active, 404);

        abort_unless($downloadItem->file && $this->disk()->exists($downloadItem->file), 404);

        $extension = pathinfo($downloadItem->file, PATHINFO_EXTENSION);
        $name = str()->slug($downloadItem->title).'.'.($extension ?: 'bin');

        return $this->disk()->download($downloadItem->file, $name);
    }

    private function disk(): Filesystem
    {
        return Storage::disk('public');
    }
}
