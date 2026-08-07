<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavbarItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NavbarController extends Controller
{
    public function index(): View
    {
        return view('admin.navbar.index', [
            'mainItems' => NavbarItem::query()->root()->ordered()->with('children')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.navbar.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['key'] = $this->uniqueKey($request->input('label'));

        NavbarItem::create($data);

        return redirect()->route('navbar.index')
            ->with('success', 'Item navbar berhasil ditambahkan.');
    }

    public function edit(NavbarItem $navbarItem): View
    {
        return view('admin.navbar.edit', compact('navbarItem'));
    }

    public function update(Request $request, NavbarItem $navbarItem): RedirectResponse
    {
        $navbarItem->update($this->validateData($request));

        $message = $navbarItem->isSubMenu()
            ? 'Sub menu berhasil diperbarui.'
            : 'Item navbar berhasil diperbarui.';

        return redirect()->route('navbar.index')
            ->with('success', $message);
    }

    public function destroy(NavbarItem $navbarItem): RedirectResponse
    {
        $navbarItem->delete();

        return redirect()->route('navbar.index')
            ->with('success', 'Item navbar berhasil dihapus.');
    }

    public function createSub(NavbarItem $navbarItem): View
    {
        return view('admin.navbar.sub.create', compact('navbarItem'));
    }

    public function storeSub(Request $request, NavbarItem $navbarItem): RedirectResponse
    {
        $data = $this->validateSubData($request);
        $data['key'] = $this->uniqueKey($request->input('label'));
        $data['parent_id'] = $navbarItem->id;
        $data['has_submenu'] = false;

        NavbarItem::create($data);

        return redirect()->route('navbar.index')
            ->with('success', 'Sub menu berhasil ditambahkan.');
    }

    public function editSub(NavbarItem $subItem): View
    {
        return view('admin.navbar.sub.edit', [
            'subItem' => $subItem,
            'parent' => $subItem->parent,
        ]);
    }

    public function updateSub(Request $request, NavbarItem $subItem): RedirectResponse
    {
        $subItem->update($this->validateSubData($request));

        return redirect()->route('navbar.index')
            ->with('success', 'Sub menu berhasil diperbarui.');
    }

    public function destroySub(NavbarItem $subItem): RedirectResponse
    {
        $subItem->delete();

        return redirect()->route('navbar.index')
            ->with('success', 'Sub menu berhasil dihapus.');
    }

    public static function icons(): array
    {
        return [
            'document' => 'Surat / Dokumen',
            'envelope' => 'Amplop',
            'calendar' => 'Kalender',
            'user' => 'Orang',
            'users' => 'Banyak Orang',
            'check' => 'Centang',
            'heart' => 'Hati',
            'home' => 'Rumah',
            'phone' => 'Telepon',
            'info' => 'Info',
        ];
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'string', 'max:255', Rule::notIn(['#'])],
            'icon' => ['nullable', 'string', 'max:50', Rule::in(array_keys(self::icons()))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'active' => ['sometimes', 'boolean'],
            'has_submenu' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $data['has_submenu'] = $request->boolean('has_submenu');

        return $data;
    }

    private function validateSubData(Request $request): array
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'string', 'max:255', Rule::notIn(['#'])],
            'icon' => ['nullable', 'string', 'max:50', Rule::in(array_keys(self::icons()))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');

        return $data;
    }

    private function uniqueKey(string $label): string
    {
        $base = Str::slug($label) ?: 'item';
        $key = $base;
        $i = 1;

        while (NavbarItem::where('key', $key)->exists()) {
            $key = $base . '-' . ++$i;
        }

        return $key;
    }
}
