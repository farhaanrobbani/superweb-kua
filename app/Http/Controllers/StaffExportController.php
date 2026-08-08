<?php

namespace App\Http\Controllers;

use App\Models\KuaSetting;
use App\Models\StaffActivity;
use App\Models\User;
use App\Support\PdfSupport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StaffExportController extends Controller
{
    public function laporanKinerja(Request $request)
    {
        $user = $this->resolveExportUser($request);
        $month = $this->month($request);
        $year = $this->year($request);

        $activities = StaffActivity::query()
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        PdfSupport::registerArialFonts();

        $pdf = Pdf::loadView('pdf.laporan-kinerja', [
            'user' => $user,
            'activities' => $activities,
            'monthName' => $this->monthName($month),
            'printDate' => $this->printDate($month, $year),
            'kepala' => $this->kepala(),
        ]);

        $fileName = sprintf(
            'Laporan_Kinerja_%s_%s_%s.pdf',
            str_replace(' ', '_', $user->name),
            $this->monthName($month),
            $year
        );

        return $pdf->download($fileName);
    }

    public function rekap(Request $request)
    {
        $user = $this->resolveExportUser($request);
        $month = $this->month($request);
        $year = $this->year($request);
        $totalHariKerja = min(max($request->integer('total_hari_kerja', 22), 0), 31);

        PdfSupport::registerArialFonts();

        $pdf = Pdf::loadView('pdf.rekap-laporan-kinerja', [
            'user' => $user,
            'month' => $month,
            'year' => $year,
            'monthName' => $this->monthName($month),
            'instansi' => KuaSetting::get('instansi', $user->instansi) ?: '',
            'kota' => KuaSetting::get('kabupaten', '') ?: '',
            'totalHariKerja' => $totalHariKerja,
            'signatureDate' => $this->signatureDate($month, $year),
            'kepala' => $this->kepala(),
            'kepalaJabatan' => trim('Kepala KUA ' . KuaSetting::get('kecamatan', '')),
        ]);

        $fileName = sprintf(
            'Rekap_Laporan_Kinerja_%s_%s_%s.pdf',
            str_replace(' ', '_', $user->name),
            $this->monthName($month),
            $year
        );

        return $pdf->download($fileName);
    }

    private function resolveExportUser(Request $request): User
    {
        if (! $request->user()->canManageContent()) {
            return $request->user();
        }

        if (! $request->filled('user_id') || $request->integer('user_id') === 0) {
            throw new HttpResponseException(
                redirect()->back()->with('error', 'Pilih pegawai terlebih dahulu sebelum melakukan export.')
            );
        }

        $user = User::find($request->integer('user_id'));

        if (! $user) {
            abort(404);
        }

        return $user;
    }

    private function month(Request $request): int
    {
        return min(max($request->integer('bulan', now()->month), 1), 12);
    }

    private function year(Request $request): int
    {
        return min(max($request->integer('tahun', now()->year), 2000), 2100);
    }

    private function monthName(int $month): string
    {
        return tanggal_indonesia(now()->month($month), 'F');
    }

    private function printDate(int $month, int $year): string
    {
        return tanggal_indonesia(Carbon::createFromDate($year, $month, 1)->endOfMonth(), 'j F Y');
    }

    private function signatureDate(int $month, int $year): string
    {
        $kota = KuaSetting::get('kabupaten', '') ?: '';

        return trim(($kota !== '' ? $kota . ', ' : '') . $this->printDate($month, $year));
    }

    private function kepala(): array
    {
        return [
            'nama' => KuaSetting::get('kepala_nama', '') ?: '',
            'nip' => KuaSetting::get('kepala_nip', '') ?: '',
            'pangkat' => KuaSetting::get('kepala_pangkat', '') ?: '',
        ];
    }
}
