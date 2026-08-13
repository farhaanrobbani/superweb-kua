<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterType;
use App\Models\Submission;
use App\Support\SubmissionForm;
use App\Support\SubmissionPdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubmissionAdminController extends Controller
{
    public function create(Request $request): View
    {
        $selectedType = null;

        if ($request->has('jenis')) {
            $selectedType = LetterType::where('code', $request->input('jenis'))
                ->where('active', true)
                ->firstOrFail();
        }

        return view('admin.submissions.create', [
            'letterTypes' => LetterType::where('active', true)->orderBy('name')->get(),
            'selectedType' => $selectedType,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'jenis' => ['required', 'exists:letter_types,code'],
            'nama_pemohon' => ['required', 'string', 'max:150'],
            'kontak' => ['required', 'string', 'max:100'],
        ]);

        $letterType = LetterType::where('code', $request->input('jenis'))
            ->where('active', true)
            ->firstOrFail();

        $request->validate(SubmissionForm::fieldRules($letterType));
        $safeData = SubmissionForm::safeData($letterType, $request);

        $submission = Submission::create([
            'letter_type_id' => $letterType->id,
            'nama_pemohon' => $request->input('nama_pemohon'),
            'kontak' => $request->input('kontak'),
            'data' => $safeData,
            'status' => Submission::STATUS_BARU,
            'token' => Str::random(40),
        ]);

        return redirect()->route('submissions.show', $submission)
            ->with('success', 'Permohonan berhasil dibuat.');
    }
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
        [$pdf, $fileName] = SubmissionPdf::build($submission);

        return $pdf->download($fileName);
    }
}
