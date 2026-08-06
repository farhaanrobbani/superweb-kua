<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KuaSetting;
use App\Models\Submission;
use App\Support\PdfSupport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = Submission::with('letterType')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('cari')) {
            $query->where('nama_pemohon', 'like', '%' . $request->input('cari') . '%');
        }

        return view('admin.submissions.index', [
            'submissions' => $query->paginate(15)->withQueryString(),
            'statuses' => Submission::statuses(),
        ]);
    }

    public function show(Submission $submission): View
    {
        $submission->load('letterType');

        return view('admin.submissions.show', compact('submission'));
    }

    public function updateStatus(Request $request, Submission $submission): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Submission::statuses()))],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $submission->update([
            'status' => $request->input('status'),
            'catatan' => $request->input('catatan'),
        ]);

        return redirect()->route('submissions.show', $submission)
            ->with('success', 'Status permohonan diperbarui.');
    }

    public function destroy(Submission $submission): RedirectResponse
    {
        $submission->delete();

        return redirect()->route('submissions.index')
            ->with('success', 'Permohonan dihapus.');
    }

    public function buatSurat(Submission $submission): RedirectResponse
    {
        return redirect()->route('letters.create', [
            'jenis' => $submission->letterType->code,
            'dari' => $submission->id,
        ]);
    }

    public function cetakPermohonan(Submission $submission)
    {
        $submission->load('letterType');

        PdfSupport::registerArialFonts();

        $kabupaten = KuaSetting::get('kabupaten') ?? '';

        $pdf = Pdf::loadView('pdf.permohonan', [
            'submission' => $submission,
            'kabupaten' => $kabupaten,
            'body' => $submission->renderPermohonanBody(),
        ])->setPaper('a4');

        $fileName = 'surat-permohonan-' . $submission->id . '.pdf';

        return $pdf->download($fileName);
    }
}
