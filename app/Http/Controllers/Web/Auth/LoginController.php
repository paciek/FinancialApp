<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Nieprawidłowy email lub hasło.'])
                ->withInput();
        }

        $request->session()->regenerate();

        $target = Route::has('dashboard') ? route('dashboard') : '/';

        return redirect()
            ->intended($target)
            ->with('success', 'Zalogowano pomyślnie.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Wylogowano.');
    }
}
