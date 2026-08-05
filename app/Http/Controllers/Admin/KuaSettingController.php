<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KuaSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KuaSettingController extends Controller
{
    private const KEYS = [
        'instansi' => 'Nama Instansi (KUA)',
        'alamat' => 'Alamat',
        'telepon' => 'Telepon',
        'email' => 'Email',
        'kecamatan' => 'Kecamatan',
        'kabupaten' => 'Kabupaten/Kota',
        'kode_pos' => 'Kode Pos',
        'kepala_nama' => 'Nama Kepala KUA',
        'kepala_nip' => 'NIP Kepala KUA',
        'kepala_pangkat' => 'Pangkat/Golongan Kepala KUA',
        'sk_kepala' => 'No. SK Pengangkatan Kepala KUA',
        'ttd_path' => 'File Tanda Tangan (path)',
        'logo_path' => 'Logo KUA (upload)',
    ];

    public function edit(): View
    {
        $settings = [];
        foreach (self::KEYS as $key => $label) {
            $settings[$key] = ['label' => $label, 'value' => KuaSetting::get($key)];
        }

        return view('admin.kua-settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'instansi' => ['required', 'string', 'max:200'],
            'alamat' => ['required', 'string', 'max:500'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'kecamatan' => ['nullable', 'string', 'max:150'],
            'kabupaten' => ['nullable', 'string', 'max:150'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'kepala_nama' => ['required', 'string', 'max:150'],
            'kepala_nip' => ['nullable', 'string', 'max:50'],
            'kepala_pangkat' => ['nullable', 'string', 'max:100'],
            'sk_kepala' => ['nullable', 'string', 'max:150'],
            'ttd_path' => ['nullable', 'string', 'max:255'],
            'logo' => ['sometimes', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'logo_hapus' => ['sometimes', 'in:1'],
        ]);

        if ($request->hasFile('logo')) {
            $old = KuaSetting::get('logo_path');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('logo')->store('logos', 'public');
            KuaSetting::set('logo_path', $path);
        } elseif ($request->boolean('logo_hapus')) {
            $old = KuaSetting::get('logo_path');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            KuaSetting::set('logo_path', '');
        }

        foreach ($validated as $key => $value) {
            KuaSetting::set($key, $value);
        }

        return redirect()->route('kua-settings.edit')
            ->with('success', 'Pengaturan KUA berhasil disimpan.');
    }
}
