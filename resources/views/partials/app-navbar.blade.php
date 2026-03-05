<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">FinancialApp</a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-gauge"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.index') }}">
                        <i class="fa-solid fa-user"></i>
                        Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.index') }}">
                        <i class="fa-solid fa-layer-group"></i>
                        Kategorie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('transactions.index') }}">
                        <i class="fa-solid fa-receipt"></i>
                        Transakcje
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('reports.expenses.byCategory') }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        Raport kategorii
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('reports.balance.overTime') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        Saldo w czasie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.password.edit') }}">
                        <i class="fa-solid fa-key"></i>
                        Zmiana hasla
                    </a>
                </li>
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Wyloguj
                </button>
            </form>
        </div>
    </div>
</nav>
