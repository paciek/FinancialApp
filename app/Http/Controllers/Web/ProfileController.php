<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' => $validated['address'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
        ]);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil zostal zaktualizowany.');
    }
}
