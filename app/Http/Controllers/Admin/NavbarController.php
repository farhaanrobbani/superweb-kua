<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavbarItem;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'description' => ['nullable', 'string', 'max:1000'],
            'embed_url' => ['nullable', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50', Rule::in(array_keys(ServiceController::icons()))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'active' => ['sometimes', 'boolean'],
            'has_submenu' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $data['has_submenu'] = $request->boolean('has_submenu');

        $navbarItem->update($data);

        $message = $navbarItem->group === NavbarItem::GROUP_TENTANG
            ? 'Sub menu berhasil diperbarui.'
            : 'Item navbar berhasil diperbarui.';

        return redirect()->route('navbar.index')
            ->with('success', $message);
    }
}
