@extends('layouts.app')

@section('title', 'Polityka prywatności - Financial APP')
@section('body_class', 'bg-light')

@section('content')
    <nav class="navbar navbar-expand-lg bg-body shadow-sm app-topbar">
        <div class="container-fluid px-3 px-lg-4 app-topbar__inner d-flex align-items-center justify-content-between gap-2">
            <a class="navbar-brand fw-semibold m-0" href="{{ route('landing.index') }}">FinancialApp</a>
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

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">
                    <div class="card border-0 shadow-sm rounded-3 fade-in">
                        <div class="card-body p-4 p-lg-5">
                            <h1 class="fs-4 fw-bold mb-4">Polityka prywatności</h1>

                            <section class="mb-4">
                                <h2 class="fs-5 fw-bold">Wprowadzenie</h2>
                                <p class="mb-0">Opis działania aplikacji finansowej.</p>
                            </section>

                            <section class="mb-4">
                                <h2 class="fs-5 fw-bold">Zbierane dane</h2>
                                <ul class="mb-0">
                                    <li>Dane konta użytkownika</li>
                                    <li>Dane transakcji finansowych</li>
                                </ul>
                            </section>

                            <section class="mb-4">
                                <h2 class="fs-5 fw-bold">Wykorzystanie danych</h2>
                                <p class="mb-0">Dane sa uzywane wylacznie do działania aplikacji.</p>
                            </section>

                            <section class="mb-4">
                                <h2 class="fs-5 fw-bold">Bezpieczeństwo</h2>
                                <p class="mb-0">Dane przechowywane są w bezpieczny sposób.</p>
                            </section>

                            <section>
                                <h2 class="fs-5 fw-bold">Kontakt</h2>
                                <p class="mb-0">
                                    <a href="mailto:contact@example.com">contact@example.com</a>
                                </p>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-4 fade-in fade-in-delay-1">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <a href="{{ url('/landing/privacy-policy') }}" class="link-secondary">Polityka prywatności</a>
            <span class="text-secondary">&copy; 2026 FinancialApp Alan Ufniarski</span>
        </div>
    </footer>
@endsection
