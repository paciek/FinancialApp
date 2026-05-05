<nav class="navbar navbar-expand-xl app-navbar sticky-top">
    <div class="container-xxl">
        <a class="navbar-brand app-navbar__brand" href="{{ route('dashboard.index') }}">
            <span class="app-navbar__logo">
                <i class="fa-solid fa-chart-simple"></i>
            </span>
            <span>FinancialApp</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Przełącz nawigację">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-3 mb-xl-0 app-navbar__links">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.index') }}">
                        <i class="fa-solid fa-gauge-high"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('transactions.index') }}">
                        <i class="fa-solid fa-list"></i>
                        Transakcje
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('budget.index') }}">
                        <i class="fa-solid fa-wallet"></i>
                        Budżet
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('reports.summary') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        Podsumowanie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.index') }}">
                        <i class="fa-solid fa-tags"></i>
                        Kategorie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('export.index') }}">
                        <i class="fa-solid fa-download"></i>
                        Eksport danych
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        <i class="fa-solid fa-user-gear"></i>
                        Profil
                    </a>
                </li>
            </ul>

            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-xl-center gap-2 app-navbar__actions">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Wyloguj
                    </button>
                </form>
                <button id="themeToggle" class="btn btn-outline-secondary btn-sm" title="Przełącz tryb jasny/ciemny" type="button">
                    <i class="fa-solid fa-moon"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
