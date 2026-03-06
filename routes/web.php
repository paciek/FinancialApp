<?php

use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\Profile\PasswordController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/landing/privacy-policy', [LandingController::class, 'privacyPolicy'])->name('landing.privacy-policy');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/reports/expenses-by-category', [ReportController::class, 'expensesByCategory'])
        ->name('reports.expenses.byCategory');
    Route::get('/reports/balance-over-time', [ReportController::class, 'balanceOverTime'])
        ->name('reports.balance.overTime');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile/password', [PasswordController::class, 'edit'])->name('profile.password.edit');
    Route::put('/profile/password', [PasswordController::class, 'update'])->name('profile.password.update');
});
