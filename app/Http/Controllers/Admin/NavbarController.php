<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavbarItem;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavbarController extends Controller
{
    public function index(): View
    {
        return view('admin.navbar.index', [
            'mainItems' => NavbarItem::query()
                ->where('group', NavbarItem::GROUP_MAIN)
                ->ordered()
                ->get(),
            'tentangItems' => NavbarItem::query()
                ->where('group', NavbarItem::GROUP_TENTANG)
                ->ordered()
                ->get(),
            'services' => Service::ordered()->paginate(15),
        ]);
    }

    public function edit(NavbarItem $navbarItem): View
    {
        return view('admin.navbar.edit', compact('navbarItem'));
    }

    public function update(Request $request, NavbarItem $navbarItem): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');

        $navbarItem->update($data);

        return redirect()->route('navbar.index')
            ->with('success', 'Item navbar berhasil diperbarui.');
    }
}
