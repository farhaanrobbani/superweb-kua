<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterTemplate;
use App\Models\LetterType;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LetterTemplateController extends Controller
{
    public function index(): View
    {
        return view('admin.letter-templates.index', [
            'templates' => LetterTemplate::with('letterType')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.letter-templates.create', [
            'letterTypes' => LetterType::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        LetterTemplate::create($data);

        return redirect()->route('letter-templates.index')
            ->with('success', 'Template surat berhasil ditambahkan.');
    }

    public function edit(LetterTemplate $letterTemplate): View
    {
        return view('admin.letter-templates.edit', [
            'letterTemplate' => $letterTemplate,
            'letterTypes' => LetterType::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LetterTemplate $letterTemplate): RedirectResponse
    {
        $letterTemplate->update($this->validateData($request));

        return redirect()->route('letter-templates.index')
            ->with('success', 'Template surat berhasil diperbarui.');
    }

    public function destroy(LetterTemplate $letterTemplate): RedirectResponse
    {
        $letterTemplate->delete();

        return redirect()->route('letter-templates.index')
            ->with('success', 'Template surat berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'letter_type_id' => ['required', 'exists:letter_types,id'],
            'name' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['body'] = HtmlSanitizer::normalize($data['body']);
        $data['active'] = $request->boolean('active');

        return $data;
    }
}
