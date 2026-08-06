<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PagePublicController extends Controller
{
    public function show(Page $page): View|RedirectResponse
    {
        if (! $page->active) {
            abort(404);
        }

        return view('public.pages.show', compact('page'));
    }
}
