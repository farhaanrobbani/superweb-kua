<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WakafService;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WakafServiceController extends Controller
{
    public function create(): View
    {
        return view('admin.wakaf-services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = Str::slug($data['name']);

        WakafService::create($data);

        return redirect()->route('pages.index', ['tab' => 'wakaf'])
            ->with('success', 'Topik berhasil ditambahkan.');
    }

    public function edit(WakafService $wakafService): View
    {
        return view('admin.wakaf-services.edit', compact('wakafService'));
    }

    public function update(Request $request, WakafService $wakafService): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = Str::slug($data['name']);

        $wakafService->update($data);

        return redirect()->route('pages.index', ['tab' => 'wakaf'])
            ->with('success', 'Topik berhasil diperbarui.');
    }

    public function destroy(WakafService $wakafService): RedirectResponse
    {
        $wakafService->delete();

        return redirect()->route('pages.index', ['tab' => 'wakaf'])
            ->with('success', 'Topik berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'persyaratan' => ['nullable', 'string', 'max:50000'],
            'alur' => ['nullable', 'string', 'max:50000'],
            'sop' => ['nullable', 'string', 'max:50000'],
            'persyaratan_label' => ['nullable', 'string', 'max:50'],
            'alur_label' => ['nullable', 'string', 'max:50'],
            'sop_label' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:50', \Illuminate\Validation\Rule::in(array_keys(self::ICONS))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'active' => ['sometimes', 'boolean'],
        ]);

        foreach (['persyaratan', 'alur', 'sop'] as $field) {
            $data[$field] = HtmlSanitizer::normalize($data[$field] ?? null);
        }

        $data['active'] = $request->boolean('active');
        $data['sort_order'] = $request->integer('sort_order');

        return $data;
    }

    public const ICONS = [
        'document' => 'Dokumen',
        'envelope' => 'Surat',
        'calendar' => 'Kalender',
        'user' => 'Orang',
        'users' => 'Orang banyak',
        'check' => 'Centang',
        'heart' => 'Hati',
        'home' => 'Rumah',
        'phone' => 'Telepon',
        'info' => 'Info',
    ];
}
