<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        Service::create($data);

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $service);

        $service->update($data);

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil dihapus.');
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

    private function uniqueSlug(string $name, ?Service $ignore = null): string
    {
        $base = Str::slug($name) ?: 'layanan';
        $slug = $base;
        $i = 2;

        while (Service::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
            ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string', 'max:50000'],
            'url' => ['nullable', 'string', 'max:255', Rule::notIn(['#'])],
            'embed_url' => ['nullable', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50', Rule::in(array_keys(self::icons()))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['content'] = HtmlSanitizer::normalize($data['content'] ?? null);
        $data['active'] = $request->boolean('active');

        return $data;
    }
}
