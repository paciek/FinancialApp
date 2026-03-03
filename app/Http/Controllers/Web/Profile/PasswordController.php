<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('profile.password');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        $newPassword = $request->string('password')->toString();

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Invalidate other browser sessions if the guard supports it.
        Auth::logoutOtherDevices($newPassword);
        $request->session()->regenerate();

        return back()->with('success', 'Hasło zostało zmienione.');
    }
}
