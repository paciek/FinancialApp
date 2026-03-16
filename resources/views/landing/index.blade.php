<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FinancialApp - Zarządzaj finansami</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-body border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="/">FinancialApp</a>
            <div class="d-flex gap-2 align-items-center">
                <a href="/login" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Zaloguj się
                </a>
                <a href="/register" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-user-plus"></i>
                    Załóż konto
                </a>
                <button id="themeToggle" class="btn btn-outline-secondary btn-sm" title="Przełącz tryb jasny/ciemny" type="button">
                    <i class="fa-solid fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="badge bg-primary-subtle text-primary mb-3 hero-badge">Nowoczesna kontrola finansów</span>
                    <h1 class="display-4 fw-semibold">
                        Zarządzaj swoimi finansami w prosty sposób
                    </h1>
                    <p class="lead text-muted">
                        Aplikacja pozwala kontrolować wydatki, analizować budżet i planować finanse w jednym miejscu.
                    </p>
                    <div class="mt-4 hero-cta">
                        <a href="/login" class="btn btn-primary btn-lg me-2">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Zaloguj się
                        </a>
                        <a href="/register" class="btn btn-outline-primary btn-lg">
                            <i class="fa-solid fa-user-plus"></i>
                            Załóż konto
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-semibold mb-3">Dlaczego FinancialApp?</h2>
                            <div class="d-flex gap-3 mb-3">
                                <div class="text-primary">
                                    <i class="fa-solid fa-chart-pie fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">Czytelne raporty</h3>
                                    <p class="text-muted mb-0">Szybko sprawdzaj, gdzie trafiają Twoje pieniądze.</p>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mb-3">
                                <div class="text-primary">
                                    <i class="fa-solid fa-wallet fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">Budżet miesięczny</h3>
                                    <p class="text-muted mb-0">Ustal limity i trzymaj się planu bez stresu.</p>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="text-primary">
                                    <i class="fa-solid fa-money-bill fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">Pełna kontrola transakcji</h3>
                                    <p class="text-muted mb-0">Dodawaj i porządkuj przychody oraz wydatki.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h2 class="fw-semibold">Zobacz, co zyskujesz</h2>
                    <p class="text-muted mb-0">
                        FinancialApp to kompletne narzędzie do zarządzania domowym budżetem.
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-money-bill fa-2x mb-3 text-primary"></i>
                            <h5 class="card-title">Zarządzanie transakcjami</h5>
                            <p class="text-muted mb-0">
                                Dodawaj przychody i wydatki, a system od razu je porządkuje.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-chart-pie fa-2x mb-3 text-primary"></i>
                            <h5 class="card-title">Raporty finansowe</h5>
                            <p class="text-muted mb-0">
                                Analizuj swoje wydatki dzięki czytelnym raportom i wykresom.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-chart-line fa-2x mb-3 text-primary"></i>
                            <h5 class="card-title">Wykresy wydatków</h5>
                            <p class="text-muted mb-0">
                                Obserwuj trendy i podejmuj lepsze decyzje finansowe.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-wallet fa-2x mb-3 text-primary"></i>
                            <h5 class="card-title">Budżet miesięczny</h5>
                            <p class="text-muted mb-0">
                                Planuj limit wydatków i monitoruj jego realizację.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-gauge-high fa-2x mb-3 text-primary"></i>
                            <h5 class="card-title">Dashboard finansowy</h5>
                            <p class="text-muted mb-0">
                                Wszystkie najważniejsze informacje w jednym widoku.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-shield-halved fa-2x mb-3 text-primary"></i>
                            <h5 class="card-title">Bezpieczny dostęp</h5>
                            <p class="text-muted mb-0">
                                Twoje dane są chronione i dostępne tylko dla Ciebie.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
