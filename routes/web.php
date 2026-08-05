<?php

use App\Http\Controllers\Admin\KuaSettingController;
use App\Http\Controllers\Admin\LetterController;
use App\Http\Controllers\Admin\LetterTemplateController;
use App\Http\Controllers\Admin\LetterTypeController;
use App\Http\Controllers\Admin\SubmissionAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/permohonan', [SubmissionController::class, 'create'])->name('permohonan.create');
Route::post('/permohonan', [SubmissionController::class, 'store'])->name('permohonan.store')->middleware('throttle:5,1');
Route::get('/permohonan/sukses', [SubmissionController::class, 'sukses'])->name('permohonan.sukses');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('letters', LetterController::class);
    Route::post('letters/{letter}/ajukan', [LetterController::class, 'ajukan'])->name('letters.ajukan');
    Route::post('letters/{letter}/setujui', [LetterController::class, 'setujui'])->name('letters.setujui')->middleware('role:kepala');
    Route::get('letters/{letter}/reject', [LetterController::class, 'reject'])->name('letters.reject')->middleware('role:kepala');
    Route::post('letters/{letter}/tolak', [LetterController::class, 'tolak'])->name('letters.tolak')->middleware('role:kepala');
    Route::post('letters/{letter}/terbitkan', [LetterController::class, 'terbitkan'])->name('letters.terbitkan');
    Route::get('letters/{letter}/pdf', [LetterController::class, 'pdf'])->name('letters.pdf');

    Route::get('/submissions', [SubmissionAdminController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [SubmissionAdminController::class, 'show'])->name('submissions.show');
    Route::put('/submissions/{submission}', [SubmissionAdminController::class, 'updateStatus'])->name('submissions.update');
    Route::delete('/submissions/{submission}', [SubmissionAdminController::class, 'destroy'])->name('submissions.destroy');
    Route::post('/submissions/{submission}/buat-surat', [SubmissionAdminController::class, 'buatSurat'])->name('submissions.buat-surat');

    Route::resource('letter-types', LetterTypeController::class);
    Route::resource('letter-templates', LetterTemplateController::class);
    Route::get('/kua-settings', [KuaSettingController::class, 'edit'])->name('kua-settings.edit');
    Route::put('/kua-settings', [KuaSettingController::class, 'update'])->name('kua-settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
