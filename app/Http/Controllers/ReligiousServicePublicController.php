<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\ReligiousService;
use Illuminate\View\View;

class ReligiousServicePublicController extends Controller
{
    public function index(): View
    {
        return view('public.keagamaan.index', [
            'religiousServices' => ReligiousService::active()->ordered()->get(),
            'page' => $this->page(),
        ]);
    }

    private function page(): ?Page
    {
        try {
            return Page::active()->where('key', 'keagamaan')->first();
        } catch (\Throwable) {
            return null;
        }
    }
}
