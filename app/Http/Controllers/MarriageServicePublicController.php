<?php

namespace App\Http\Controllers;

use App\Models\MarriageService;
use App\Models\Page;
use Illuminate\View\View;

class MarriageServicePublicController extends Controller
{
    public function index(): View
    {
        return view('public.pernikahan.index', [
            'marriageServices' => MarriageService::active()->ordered()->get(),
            'page' => $this->page(),
        ]);
    }

    private function page(): ?Page
    {
        try {
            return Page::active()->where('key', 'pernikahan')->first();
        } catch (\Throwable) {
            return null;
        }
    }
}
