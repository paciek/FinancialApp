<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    @include('partials.frontend-assets')
</head>
<body class="app-shell">
    @include('partials.navbar')
    @include('partials.alerts')

    <main class="app-main">
    <div class="container-xxl">
        <div class="page-header">
            <div>
                <span class="page-header__eyebrow">
                    <i class="fa-solid fa-chart-pie"></i>
                    Przegląd finansów
                </span>
                <h1 class="page-header__title">Dashboard</h1>
                <p class="page-header__description">Najważniejsze podsumowanie Twoich finansów w jednym, czytelnym widoku.</p>
            </div>
        </div>
        @if ($budgetExceeded)
            <div class="alert alert-danger d-flex align-items-center mt-3">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <div>
                    Przekroczyłeś budżet miesięczny.
                    Limit: {{ number_format((float) ($budget?->limit_amount ?? 0), 2, '.', ' ') }}
                    Wydano: {{ number_format((float) $spent, 2, '.', ' ') }}
                    @if ($percentage > 0)
                        ({{ $percentage }}%)
                    @endif
                </div>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card surface-card border-0 h-100">
                    <div class="card-body stats-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stats-card__label">Przychody</p>
                                <div class="stats-card__value text-success">{{ $income }}</div>
                            </div>
                            <span class="stats-card__icon text-success"><i class="fa-solid fa-arrow-trend-up"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card surface-card border-0 h-100">
                    <div class="card-body stats-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stats-card__label">Wydatki</p>
                                <div class="stats-card__value text-danger">{{ $expenses }}</div>
                            </div>
                            <span class="stats-card__icon text-danger"><i class="fa-solid fa-arrow-trend-down"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card surface-card border-0 h-100">
                    <div class="card-body stats-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stats-card__label">Saldo</p>
                                <div class="stats-card__value text-primary">{{ $balance }}</div>
                            </div>
                            <span class="stats-card__icon text-primary"><i class="fa-solid fa-wallet"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-3">
            <div class="col-md-6">
                <div class="card surface-card border-0 h-100">
                    <div class="card-body">
                        <div class="section-card__header">
                            <div>
                                <h2 class="section-card__title">Ostatnie transakcje</h2>
                                <p class="section-card__description">Szybki podgląd ostatnio dodanych wpisów.</p>
                            </div>
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary btn-sm">
                                Zobacz wszystkie
                            </a>
                        </div>

                        @if ($recentTransactions->isEmpty())
                            <p class="text-muted mb-0">Brak transakcji do wyświetlenia.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table data-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Kwota</th>
                                            <th>Typ</th>
                                            <th>Kategoria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentTransactions as $transaction)
                                            @php
                                                $typeLabel = $transaction->type === 'income' ? 'Przychód' : 'Wydatek';
                                            @endphp
                                            <tr>
                                                <td>{{ $transaction->transaction_date?->format('Y-m-d') }}</td>
                                                <td>{{ number_format((float) $transaction->amount, 2, '.', ' ') }}</td>
                                                <td>
                                                    <span class="badge {{ $transaction->type === 'income' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $typeLabel }}
                                                    </span>
                                                </td>
                                                <td>{{ $transaction->category?->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card surface-card border-0 h-100">
                    <div class="card-body">
                        <h2 class="section-card__title mb-3">
                            <i class="fa-solid fa-chart-pie"></i>
                            Statystyki transakcji
                        </h2>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Liczba transakcji</div>
                                    <div class="h5 mb-0">{{ $totalTransactions }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Przychody / wydatki</div>
                                    <div class="h6 mb-0 text-success">{{ $incomeCount }}</div>
                                    <div class="h6 mb-0 text-danger">{{ $expenseCount }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Średni przychód</div>
                                    <div class="h6 mb-0 text-success">{{ number_format($averageIncome, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Średni wydatek</div>
                                    <div class="h6 mb-0 text-danger">{{ number_format($averageExpense, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Najwyższy przychód</div>
                                    <div class="h6 mb-0 text-success">{{ number_format($largestIncome, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Najwyższy wydatek</div>
                                    <div class="h6 mb-0 text-danger">{{ number_format($largestExpense, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Najwyższa kategoria wydatków</div>
                                    <div class="h6 mb-0">
                                        @if ($topExpenseCategory)
                                            {{ $topExpenseCategory->category?->name ?? '-' }} ({{ number_format((float) $topExpenseCategory->total, 2, '.', ' ') }})
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
</body>
</html>






