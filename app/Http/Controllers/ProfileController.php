<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['foto_profil'], $data['foto_hapus']);

        $user = $request->user();
        $user->fill($data);

        if ($request->hasFile('foto_profil')) {
            $this->deleteFoto($user);

            $user->foto_profil_url = $request->file('foto_profil')->store('users/photos', 'public');
        } elseif ($request->boolean('foto_hapus')) {
            $this->deleteFoto($user);

            $user->foto_profil_url = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    private function deleteFoto($user): void
    {
        if ($user->foto_profil_url && Storage::disk('public')->exists($user->foto_profil_url)) {
            Storage::disk('public')->delete($user->foto_profil_url);
        }
    }
}
