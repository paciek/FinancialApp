<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm app-topbar">
    <div class="container-fluid px-3 px-lg-4 app-topbar__inner d-flex align-items-center justify-content-between gap-2">
        <a class="navbar-brand fw-semibold m-0" href="{{ auth()->check() ? route('dashboard') : route('register') }}">
            FinancialApp
        </a>
        <div class="d-flex align-items-center gap-2 ms-auto">
            @auth
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

<aside class="right-nav-tabs">
    <div class="nav nav-pills flex-column gap-2">
        @auth
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-gauge me-1"></i>
                Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                <i class="fa-solid fa-user me-1"></i>
                Profil
            </a>
            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                <i class="fa-solid fa-receipt me-1"></i>
                Transakcje
            </a>
            <a class="nav-link {{ request()->routeIs('budget.*') ? 'active' : '' }}" href="{{ route('budget.index') }}">
                <i class="fa-solid fa-wallet me-1"></i>
                Budzet
            </a>
            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                <i class="fa-solid fa-layer-group me-1"></i>
                Kategorie
            </a>
            <a class="nav-link {{ request()->routeIs('reports.expenses.byCategory') ? 'active' : '' }}" href="{{ route('reports.expenses.byCategory') }}">
                <i class="fa-solid fa-chart-pie me-1"></i>
                Raport
            </a>
            <a class="nav-link {{ request()->routeIs('reports.balance.overTime') ? 'active' : '' }}" href="{{ route('reports.balance.overTime') }}">
                <i class="fa-solid fa-chart-line me-1"></i>
                Saldo
            </a>
            <a class="nav-link {{ request()->routeIs('profile.password.*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                <i class="fa-solid fa-key me-1"></i>
                Haslo
            </a>
        @else
            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                <i class="fa-solid fa-right-to-bracket me-1"></i>
                Logowanie
            </a>
            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                <i class="fa-solid fa-user-plus me-1"></i>
                Rejestracja
            </a>
        @endauth
    </div>
</aside>
