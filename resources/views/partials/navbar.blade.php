<nav class="navbar navbar-expand-lg bg-body border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('transactions.index') }}">FinancialApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Przelacz nawigacje">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                    <a class="nav-link" href="{{ route('reports.summary') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        Podsumowanie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('export.index') }}">
                        <i class="fa-solid fa-download"></i>
                        Eksport danych
                    </a>
                </li>
            </ul>
            <button id="themeToggle" class="btn btn-outline-secondary btn-sm ms-2" title="Przelacz tryb jasny/ciemny" type="button">
                <i id="themeIcon" class="fa-solid fa-moon"></i>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="d-flex ms-2">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    Wyloguj
                </button>
            </form>
        </div>
    </div>
</nav>

