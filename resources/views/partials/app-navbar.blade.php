<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ auth()->check() ? route('dashboard') : route('register') }}">
            FinancialApp
        </a>
        <div class="d-flex align-items-center gap-2 ms-auto">
            @auth
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge"></i>
                    Dashboard
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('profile.index') }}">
                    <i class="fa-solid fa-user"></i>
                    Profil
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('transactions.index') }}">
                    <i class="fa-solid fa-receipt"></i>
                    Transakcje
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('categories.index') }}">
                    <i class="fa-solid fa-layer-group"></i>
                    Kategorie
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('reports.expenses.byCategory') }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    Raport
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('reports.balance.overTime') }}">
                    <i class="fa-solid fa-chart-line"></i>
                    Saldo w czasie
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('profile.password.edit') }}">
                    <i class="fa-solid fa-key"></i>
                    Zmiana hasla
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Wyloguj
                    </button>
                </form>
            @else
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Logowanie
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('register') }}">
                    <i class="fa-solid fa-user-plus"></i>
                    Rejestracja
                </a>
            @endauth

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
