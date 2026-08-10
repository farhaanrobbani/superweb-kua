<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\WakafService;
use Illuminate\View\View;

class WakafServicePublicController extends Controller
{
    public function index(): View
    {
        return view('public.wakaf.index', [
            'wakafServices' => WakafService::active()->ordered()->get(),
            'page' => $this->page(),
        ]);
    }

    private function page(): ?Page
    {
        try {
            return Page::active()->where('key', 'wakaf')->first();
        } catch (\Throwable) {
            return null;
        }
    }
}
