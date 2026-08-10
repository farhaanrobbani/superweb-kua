<?php

namespace App\Http\Controllers;

use App\Models\KuaActivityTheme;
use App\Models\UserActivityTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffTemplateController extends Controller
{
    public function index(Request $request): View
    {
        $templates = $request->user()->activityTemplates
            ->mapWithKeys(fn (UserActivityTemplate $template) => [
                $template->activity_type_key => [
                    'kegiatan' => $template->kegiatan,
                    'pekerjaan' => $template->pekerjaan,
                ],
            ])
            ->all();

        return view('staff-activities.templates', [
            'themes' => KuaActivityTheme::activeList(),
            'templates' => $templates,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $keys = array_keys(KuaActivityTheme::activeList());

        $validated = $request->validate([
            'templates' => ['required', 'array'],
            'templates.*.kegiatan' => ['nullable', 'string', 'max:1000'],
            'templates.*.pekerjaan' => ['nullable', 'string', 'max:1000'],
        ]);

        $keys = array_intersect(array_keys($validated['templates']), array_keys(KuaActivityTheme::activeList()));

        $saved = 0;
        $deleted = 0;

        foreach ($keys as $key) {
            $value = $validated['templates'][$key];
            $kegiatan = trim((string) ($value['kegiatan'] ?? ''));
            $pekerjaan = trim((string) ($value['pekerjaan'] ?? ''));

            if ($kegiatan === '' && $pekerjaan === '') {
                $deleted += $request->user()->activityTemplates()
                    ->where('activity_type_key', $key)
                    ->delete();

                continue;
            }

            $request->user()->activityTemplates()->updateOrCreate(
                ['activity_type_key' => $key],
                ['kegiatan' => $kegiatan, 'pekerjaan' => $pekerjaan]
            );
            $saved++;
        }

        $message = "{$saved} template kalimat berhasil disimpan.";

        if ($deleted > 0) {
            $message .= " {$deleted} template dihapus.";
        }

        return back()->with('success', $message);
    }
}
