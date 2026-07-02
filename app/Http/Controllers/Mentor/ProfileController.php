<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        $mentor = $user->mentor;

        return view('mentor.profile.edit', compact('user', 'mentor'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $mentor = $user->mentor;

        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'bio'             => ['nullable', 'string', 'max:1000'],
            'certification'   => ['nullable', 'string', 'max:255'],
            'specialization'  => ['nullable', 'string', 'max:255'],
        ]);

        $user->update(['name' => $validated['name']]);

        $mentor->update([
            'full_name'      => $validated['name'],
            'bio'            => $validated['bio'] ?? null,
            'certification'  => $validated['certification'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
        ]);

        return redirect()
            ->route('mentor.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
