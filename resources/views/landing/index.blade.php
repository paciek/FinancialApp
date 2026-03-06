@extends('layouts.app')

@section('title', 'Financial APP - Strona główna')
@section('body_class', 'bg-light')

@section('content')
    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Financial APP</a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <a class="btn btn-outline-secondary btn-sm" href="{{ url('/login') }}">Logowanie</a>
                <a class="btn btn-primary btn-sm" href="{{ url('/register') }}">Rejestracja</a>
                <button
                    id="themeToggle"
                    class="btn btn-outline-secondary btn-sm"
                    title="Przełącz tryb jasny/ciemny"
                    aria-label="Przełącz tryb jasny/ciemny"
                >
                    <i class="fa-solid fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <main>
        <section class="py-5 py-lg-5 bg-body">
            <div class="container py-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-12 col-lg-8">
                        <h1 class="display-5 fw-bold mb-3">Zarządzaj swoimi finansami w jednym miejscu</h1>
                        <p class="lead text-secondary mb-4">
                            Kontroluj swoje przychody i wydatki, analizuj dane finansowe oraz buduj zdrowe nawyki finansowe.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ url('/register') }}" class="btn btn-primary btn-lg">Rozpocznij teraz</a>
                            <a href="{{ url('/login') }}" class="btn btn-outline-secondary btn-lg">Zaloguj się</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="mb-3 text-primary fs-2">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <h2 class="h5">Śledzenie wydatków</h2>
                                <p class="text-secondary mb-0">Zapisuj wszystkie swoje przychody i wydatki.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="mb-3 text-primary fs-2">
                                    <i class="fa-solid fa-chart-pie"></i>
                                </div>
                                <h2 class="h5">Raporty finansowe</h2>
                                <p class="text-secondary mb-0">Analizuj swoje finanse dzięki czytelnym wykresom.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="mb-3 text-primary fs-2">
                                    <i class="fa-solid fa-piggy-bank"></i>
                                </div>
                                <h2 class="h5">Kontrola budżetu</h2>
                                <p class="text-secondary mb-0">Kontroluj saldo i planuj budżet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-white border-top border-bottom">
            <div class="container text-center">
                <p class="h4 mb-3">Zacznij zarządzać swoimi finansami już dziś.</p>
                <a href="{{ url('/register') }}" class="btn btn-success btn-lg">Utwórz darmowe konto</a>
            </div>
        </section>
    </main>

    <footer class="py-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <a href="{{ url('/landing/privacy-policy') }}" class="link-secondary">Polityka prywatności</a>
            <span class="text-secondary">© 2026 FinancialApp Alan Ufniarski</span>
        </div>
    </footer>
@endsection
