<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    @include('partials.navbar')
    @include('partials.alerts')

    <div class="container py-5">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="h6 text-uppercase text-muted">Przychody</h2>
                                <div class="h4 mb-0 text-success">{{ $income }}</div>
                            </div>
                            <i class="fa-solid fa-arrow-up fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="h6 text-uppercase text-muted">Wydatki</h2>
                                <div class="h4 mb-0 text-danger">{{ $expenses }}</div>
                            </div>
                            <i class="fa-solid fa-arrow-down fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="h6 text-uppercase text-muted">Saldo</h2>
                                <div class="h4 mb-0 text-primary">{{ $balance }}</div>
                            </div>
                            <i class="fa-solid fa-wallet fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-3">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="h6 text-uppercase text-muted mb-0">Ostatnie transakcje</h2>
                            <a href="{{ route('transactions.index') }}" class="text-decoration-none">
                                Zobacz wszystkie
                            </a>
                        </div>

                        @if ($recentTransactions->isEmpty())
                            <p class="text-muted mb-0">Brak transakcji do wyswietlenia.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
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
                                                $typeLabel = $transaction->type === 'income' ? 'Przychod' : 'Wydatek';
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
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h6 text-uppercase text-muted mb-3">
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
                                    <div class="text-muted text-uppercase small">Sredni przychod</div>
                                    <div class="h6 mb-0 text-success">{{ number_format($averageIncome, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Sredni wydatek</div>
                                    <div class="h6 mb-0 text-danger">{{ number_format($averageExpense, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Najwyzszy przychod</div>
                                    <div class="h6 mb-0 text-success">{{ number_format($largestIncome, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Najwyzszy wydatek</div>
                                    <div class="h6 mb-0 text-danger">{{ number_format($largestExpense, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Najwyzsza kategoria wydatkow</div>
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
</body>
</html>
