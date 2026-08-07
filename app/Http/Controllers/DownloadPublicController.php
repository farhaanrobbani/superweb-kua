<?php

namespace App\Http\Controllers;

use App\Models\DownloadItem;
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

        return view('public.unduhan.index', [
            'categories' => $categories,
            'total' => $items->count(),
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
