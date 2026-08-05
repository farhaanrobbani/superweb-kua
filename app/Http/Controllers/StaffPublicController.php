<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\View\View;

class StaffPublicController extends Controller
{
    public function index(): View
    {
        $grouped = Staff::active()->ordered()->get()->groupBy(function (Staff $staff) {
            return $staff->bagian ?: 'Pegawai';
        });

        return view('public.staff.index', [
            'groups' => $grouped,
            'all' => Staff::active()->ordered()->get(),
        ]);
    }
}
