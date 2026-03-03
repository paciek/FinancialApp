<?php

use App\Http\Controllers\Web\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/register');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

    Route::view('/login', 'auth.login')->name('login');
});
