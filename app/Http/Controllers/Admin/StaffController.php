<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        return view('admin.staff.index', [
            'staff' => Staff::ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Staff::create($this->validatedWithFoto($request));

        return redirect()->route('staff.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Staff $staff): View
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff): RedirectResponse
    {
        $staff->update($this->validatedWithFoto($request, $staff));

        return redirect()->route('staff.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        if ($staff->foto && Storage::disk('public')->exists($staff->foto)) {
            Storage::disk('public')->delete($staff->foto);
        }

        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }

    private function validatedWithFoto(Request $request, ?Staff $staff = null): array
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'nip' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['required', 'string', 'max:150'],
            'pangkat_golongan' => ['nullable', 'string', 'max:100'],
            'bagian' => ['nullable', 'string', 'max:150'],
            'foto' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'foto_hapus' => ['sometimes', 'in:1'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('foto')) {
            if ($staff?->foto && Storage::disk('public')->exists($staff->foto)) {
                Storage::disk('public')->delete($staff->foto);
            }

            $data['foto'] = $request->file('foto')->store('staff', 'public');
        } elseif ($request->boolean('foto_hapus')) {
            if ($staff?->foto && Storage::disk('public')->exists($staff->foto)) {
                Storage::disk('public')->delete($staff->foto);
            }

            $data['foto'] = null;
        }

        return $data;
    }
}
