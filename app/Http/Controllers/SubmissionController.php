<?php

namespace App\Http\Controllers;

use App\Models\KuaSetting;
use App\Models\LetterType;
use App\Models\Page;
use App\Models\Submission;
use App\Support\SubmissionForm;
use App\Support\SubmissionPdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function create(Request $request): View
    {
        $selectedType = null;
        $data = [];

        if ($request->has('jenis')) {
            $selectedType = LetterType::where('code', $request->input('jenis'))->where('active', true)->publik()->firstOrFail();
            $data = old('data', []);
        }

        return view('public.submissions.create', [
            'letterTypes' => LetterType::where('active', true)->publik()->orderBy('name')->get(),
            'selectedType' => $selectedType,
            'data' => $data,
            'service' => Page::query()->where('key', 'layanan-permohonan')->active()->first(),
            'kua' => [
                'instansi' => KuaSetting::get('instansi'),
                'alamat' => KuaSetting::get('alamat'),
                'telepon' => KuaSetting::get('telepon'),
                'email' => KuaSetting::get('email'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('website')) {
            abort(403, 'Permohonan ditolak.');
        }

        $request->validate([
            'jenis' => ['required', 'exists:letter_types,code'],
            'nama_pemohon' => ['required', 'string', 'max:150'],
            'kontak' => ['required', 'string', 'max:100'],
        ]);

        $letterType = LetterType::where('code', $request->input('jenis'))->publik()->firstOrFail();

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

        session(['permohonan_unduh' => $submission->token]);

        return redirect()->route('permohonan.sukses');
    }

    public function download(string $token)
    {
        $submission = Submission::where('token', $token)->firstOrFail();

        [$pdf, $fileName] = SubmissionPdf::build($submission);

        return $pdf->download($fileName);
    }

    public function sukses(): View
    {
        return view('public.submissions.sukses');
    }
}

