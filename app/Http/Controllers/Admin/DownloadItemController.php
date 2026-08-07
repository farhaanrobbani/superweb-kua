<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DownloadItemController extends Controller
{
    public function index(): View
    {
        return view('admin.download-items.index', [
            'downloadItems' => DownloadItem::orderBy('sort_order')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.download-items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        DownloadItem::create($this->validatedWithFile($request));

        return redirect()->route('download-items.index')
            ->with('success', 'Berkas berhasil ditambahkan.');
    }

    public function edit(DownloadItem $downloadItem): View
    {
        return view('admin.download-items.edit', compact('downloadItem'));
    }

    public function update(Request $request, DownloadItem $downloadItem): RedirectResponse
    {
        $downloadItem->update($this->validatedWithFile($request, $downloadItem));

        return redirect()->route('download-items.index')
            ->with('success', 'Berkas berhasil diperbarui.');
    }

    public function destroy(DownloadItem $downloadItem): RedirectResponse
    {
        $this->deleteFile($downloadItem);

        $downloadItem->delete();

        return redirect()->route('download-items.index')
            ->with('success', 'Berkas berhasil dihapus.');
    }

    private function validatedWithFile(Request $request, ?DownloadItem $downloadItem = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => ['nullable', 'string', 'max:100'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,webp,zip', 'max:10240'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'file_hapus' => ['sometimes', 'in:1'],
            'active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['active'] = $request->boolean('active');
        $data['sort_order'] = $request->integer('sort_order');

        if ($request->hasFile('file')) {
            $this->deleteFile($downloadItem);

            $data['file'] = $request->file('file')->store('downloads', 'public');
            $data['external_url'] = null;
        } elseif ($request->boolean('file_hapus')) {
            $this->deleteFile($downloadItem);

            $data['file'] = null;
        }

        if (blank($data['file'] ?? null) && blank($data['external_url'] ?? null)) {
            throw ValidationException::withMessages([
                'file' => 'Unggah file atau isi URL eksternal (salah satu wajib).',
            ]);
        }

        return $data;
    }

    private function deleteFile(?DownloadItem $downloadItem): void
    {
        if ($downloadItem?->file && Storage::disk('public')->exists($downloadItem->file)) {
            Storage::disk('public')->delete($downloadItem->file);
        }
    }
}
