<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KuaDailyData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KuaDailyController extends Controller
{
    public function index(Request $request): View
    {
        [$month, $year] = $this->period($request);

        $data = KuaDailyData::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal')
            ->get();

        return view('admin.kua-daily.index', [
            'data' => $data,
            'columns' => KuaDailyData::ACTIVITY_COLUMNS,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function create(Request $request): View
    {
        $tanggal = $request->query('tanggal');

        return view('admin.kua-daily.create', [
            'columns' => KuaDailyData::ACTIVITY_COLUMNS,
            'tanggal' => $tanggal,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        KuaDailyData::updateOrCreate(
            ['tanggal' => $data['tanggal']],
            [...$data, 'created_by' => $request->user()->id]
        );

        return $this->redirectToPeriod($data['tanggal'])
            ->with('success', 'Data harian berhasil disimpan.');
    }

    public function edit(KuaDailyData $kuaDaily): View
    {
        return view('admin.kua-daily.edit', [
            'columns' => KuaDailyData::ACTIVITY_COLUMNS,
            'data' => $kuaDaily,
        ]);
    }

    public function update(Request $request, KuaDailyData $kuaDaily): RedirectResponse
    {
        $data = $this->validateData($request);

        $kuaDaily->update($data);

        return $this->redirectToPeriod($data['tanggal'])
            ->with('success', 'Data harian berhasil diperbarui.');
    }

    public function destroy(KuaDailyData $kuaDaily): RedirectResponse
    {
        $kuaDaily->delete();

        return back()->with('success', 'Data harian berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $rules = [
            'tanggal' => ['required', 'date'],
        ];

        foreach (array_keys(KuaDailyData::ACTIVITY_COLUMNS) as $column) {
            $rules[$column] = ['nullable', 'integer', 'min:0', 'max:1000000'];
        }

        return $request->validate($rules);
    }

    private function redirectToPeriod(string $tanggal): RedirectResponse
    {
        [$year, $month] = explode('-', $tanggal);

        return redirect()->route('kua-daily.index', [
            'bulan' => (int) $month,
            'tahun' => (int) $year,
        ]);
    }

    private function period(Request $request): array
    {
        $month = min(max($request->integer('bulan', now()->month), 1), 12);
        $year = min(max($request->integer('tahun', now()->year), 2000), 2100);

        return [$month, $year];
    }
}
