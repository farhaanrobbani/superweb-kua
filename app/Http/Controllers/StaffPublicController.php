<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Staff;
use Illuminate\View\View;

class StaffPublicController extends Controller
{
    public function index(): View
    {
        $grouped = Staff::active()->ordered()->get()->groupBy(function (Staff $staff) {
            return $staff->bagian ?: 'Pegawai';
        });

        try {
            $page = Page::query()->where('key', 'pegawai')->active()->first();
        } catch (\Throwable) {
            $page = null;
        }

        return view('public.staff.index', [
            'groups' => $grouped,
            'all' => Staff::active()->ordered()->get(),
            'page' => $page,
        ]);
    }
}
