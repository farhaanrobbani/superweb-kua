<?php

namespace App\Http\Controllers;

use App\Models\MarriageService;
use Illuminate\View\View;

class MarriageServicePublicController extends Controller
{
    public function index(): View
    {
        return view('public.pernikahan.index', [
            'services' => MarriageService::active()->ordered()->get(),
        ]);
    }
}
