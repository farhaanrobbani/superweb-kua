<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarriageAnnouncement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarriageAnnouncementController extends Controller
{
    public function index(): View
    {
        return view('admin.marriage-announcements.index', [
            'announcements' => MarriageAnnouncement::query()
                ->orderByDesc('tanggal_akad')
                ->orderByDesc('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.marriage-announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        MarriageAnnouncement::create($this->validated($request));

        return redirect()->route('marriage-announcements.index')
            ->with('success', 'Pengumuman kehendak nikah berhasil ditambahkan.');
    }

    public function edit(MarriageAnnouncement $marriageAnnouncement): View
    {
        return view('admin.marriage-announcements.edit', [
            'announcement' => $marriageAnnouncement,
        ]);
    }

    public function update(Request $request, MarriageAnnouncement $marriageAnnouncement): RedirectResponse
    {
        $marriageAnnouncement->update($this->validated($request));

        return redirect()->route('marriage-announcements.index')
            ->with('success', 'Pengumuman kehendak nikah berhasil diperbarui.');
    }

    public function destroy(MarriageAnnouncement $marriageAnnouncement): RedirectResponse
    {
        $marriageAnnouncement->delete();

        return redirect()->route('marriage-announcements.index')
            ->with('success', 'Pengumuman kehendak nikah berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'nama_pria' => ['required', 'string', 'max:150'],
            'asal_pria' => ['nullable', 'string', 'max:255'],
            'nama_wanita' => ['required', 'string', 'max:150'],
            'asal_wanita' => ['nullable', 'string', 'max:255'],
            'tanggal_akad' => ['required', 'date'],
            'tempat_nikah' => ['nullable', 'string', 'max:255'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['nama_pria'] = trim($data['nama_pria']);
        $data['nama_wanita'] = trim($data['nama_wanita']);
        $data['asal_pria'] = isset($data['asal_pria']) ? trim($data['asal_pria']) : null;
        $data['asal_wanita'] = isset($data['asal_wanita']) ? trim($data['asal_wanita']) : null;
        $data['tempat_nikah'] = isset($data['tempat_nikah']) ? trim($data['tempat_nikah']) : null;
        $data['active'] = $request->boolean('active');

        return $data;
    }
}
