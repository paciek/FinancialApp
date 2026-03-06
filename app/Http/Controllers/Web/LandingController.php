<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing.index');
    }

    public function privacyPolicy(): View
    {
        return view('landing.privacy-policy');
    }
}
