<nav class="navbar navbar-expand-lg bg-body border-bottom shadow-sm fixed-top">
    <div class="container h-100">
        <a class="navbar-brand fw-semibold" href="{{ route('transactions.index') }}">FinancialApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Przełącz nawigację">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse h-100" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
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
            <form method="POST" action="{{ route('logout') }}" class="d-flex ms-2 align-items-center">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Wyloguj
                </button>
            </form>
            <button id="themeToggle" class="btn btn-outline-secondary btn-sm ms-2 align-self-center" title="Przełącz tryb jasny/ciemny" type="button">
                <i class="fa-solid fa-moon"></i>
            </button>
        </div>
    </div>
</nav>
<div style="height: 60px;"></div>



