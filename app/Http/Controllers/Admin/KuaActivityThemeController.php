<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KuaActivityTheme;
use App\Models\KuaDailyData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KuaActivityThemeController extends Controller
{
    public function index(): View
    {
        return view('admin.kua-themes.index', [
            'themes' => KuaActivityTheme::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.kua-themes.create', [
            'theme' => new KuaActivityTheme,
            'nextOrder' => KuaActivityTheme::query()->max('sort_order') + 1,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $data['key'] = $this->uniqueKey(($data['key'] ?? '') ?: Str::slug($data['label'], '_'));

        KuaActivityTheme::create($data);

        return redirect()->route('kua-themes.index')
            ->with('success', 'Tema pekerjaan berhasil ditambahkan.');
    }

    public function edit(KuaActivityTheme $kuaActivityTheme): View
    {
        return view('admin.kua-themes.edit', [
            'theme' => $kuaActivityTheme,
        ]);
    }

    public function update(Request $request, KuaActivityTheme $kuaActivityTheme): RedirectResponse
    {
        $data = $this->validateData($request, $kuaActivityTheme);

        $kuaActivityTheme->update($data);

        return redirect()->route('kua-themes.index')
            ->with('success', 'Tema pekerjaan berhasil diperbarui.');
    }

    public function destroy(KuaActivityTheme $kuaActivityTheme): RedirectResponse
    {
        $key = $kuaActivityTheme->key;

        KuaDailyData::query()
            ->whereNotNull('data')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($key) {
                foreach ($rows as $row) {
                    if (isset($row->data[$key])) {
                        $data = $row->data;
                        unset($data[$key]);
                        $row->update(['data' => $data]);
                    }
                }
            });

        $kuaActivityTheme->delete();

        return redirect()->route('kua-themes.index')
            ->with('success', 'Tema pekerjaan berhasil dihapus permanen beserta nilainya pada data harian.');
    }

    public function move(Request $request, KuaActivityTheme $kuaActivityTheme): RedirectResponse
    {
        $direction = $request->string('direction')->toString() === 'up' ? 'up' : 'down';
        $query = KuaActivityTheme::query();

        $neighbor = $direction === 'up'
            ? $query->where('sort_order', '<', $kuaActivityTheme->sort_order)->orderByDesc('sort_order')->first()
            : $query->where('sort_order', '>', $kuaActivityTheme->sort_order)->orderBy('sort_order')->first();

        if ($neighbor) {
            [$kuaActivityTheme->sort_order, $neighbor->sort_order] = [$neighbor->sort_order, $kuaActivityTheme->sort_order];
            $kuaActivityTheme->save();
            $neighbor->save();
        }

        return redirect()->route('kua-themes.index');
    }

    private function validateData(Request $request, ?KuaActivityTheme $theme = null): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'key' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9_]+$/', Rule::unique('kua_activity_themes', 'key')->ignore($theme)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'active' => ['nullable', 'boolean'],
        ]);
    }

    private function uniqueKey(string $key): string
    {
        $candidate = $key;
        $suffix = 2;

        while (KuaActivityTheme::query()->where('key', $candidate)->exists()) {
            $candidate = $key.'_'.$suffix;
            $suffix++;
        }

        return $candidate;
    }
}
