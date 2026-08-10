<?php

namespace App\Http\Controllers;

use App\Models\KuaSetting;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FaviconController extends Controller
{
    public function __invoke(): BinaryFileResponse
    {
        $path = KuaSetting::get('logo_path');

        if (! blank($path) && Storage::disk('public')->exists($path)) {
            return response()->file(
                Storage::disk('public')->path($path),
                ['Content-Type' => Storage::disk('public')->mimeType($path)]
            );
        }

        return response()->file(public_path('favicon.ico'));
    }
}
