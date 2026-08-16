<?php

namespace App\Http\Controllers;

use App\Models\NavbarItem;
use Illuminate\Http\Request;

class NavbarPageController extends Controller
{
    public function resolve(Request $request)
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            abort(404);
        }

        $path = '/'.trim($request->path(), '/');

        try {
            $item = NavbarItem::query()
                ->active()
                ->whereNotNull('url')
                ->where('url', '!=', '')
                ->ordered()
                ->get()
                ->first(fn (NavbarItem $item) => $path === $item->url || str_starts_with($path, $item->url.'/'));
        } catch (\Throwable) {
            abort(404);
        }

        if (! $item) {
            abort(404);
        }

        $rest = ltrim(substr($path, strlen((string) $item->url)), '/');

        return match ($item->key) {
            'pengumuman' => $rest === ''
                ? app(AnnouncementPublicController::class)->index($request)
                : app(AnnouncementPublicController::class)->showBySlug($rest),
            'pernikahan' => app(MarriageServicePublicController::class)->index(),
            'wakaf' => app(WakafServicePublicController::class)->index(),
            'keagamaan' => app(ReligiousServicePublicController::class)->index(),
            'layanan-permohonan' => app(SubmissionController::class)->create($request),
            'cari-akta' => app(LayananController::class)->cariAkta(),
            'pegawai' => app(StaffPublicController::class)->index(),
            'unduhan' => app(DownloadPublicController::class)->index(),
            'kritik-saran' => app(KritikSaranPublicController::class)->create(),
            default => abort(404),
        };
    }
}
