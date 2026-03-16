<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }

        return view('landing.index');
    }
}
