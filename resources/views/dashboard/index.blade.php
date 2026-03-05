<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
@include('partials.app-navbar')
<div class="container py-4">
    @include('partials.alerts')

    <h1 class="h4 mb-3">
        <i class="fa-solid fa-gauge"></i>
        Dashboard
    </h1>

    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card shadow-sm bg-success-subtle border-0">
                <div class="card-body">
                    <h6>
                        <i class="fa-solid fa-arrow-trend-up text-success"></i>
                        Przychody
                    </h6>
                    <h4>{{ number_format($totalIncome, 2) }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm bg-danger-subtle border-0">
                <div class="card-body">
                    <h6>
                        <i class="fa-solid fa-arrow-trend-down text-danger"></i>
                        Wydatki
                    </h6>
                    <h4>{{ number_format($totalExpense, 2) }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>
                        <i class="fa-solid fa-scale-balanced"></i>
                        Saldo
                    </h6>
                    <h4 class="{{ $balance < 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($balance, 2) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm bg-primary-subtle border-0">
                <div class="card-body">
                    <h6>
                        <i class="fa-solid fa-receipt"></i>
                        Transakcje
                    </h6>
                    <h4>{{ $transactionCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h5 mb-3">Ostatnie transakcje</h2>

            @if ($latestTransactions->count())
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th>Data</th>
                            <th>Kwota</th>
                            <th>Typ</th>
                            <th>Kategoria</th>
                            <th>Opis</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($latestTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                                <td>{{ number_format((float) $transaction->amount, 2) }}</td>
                                <td>
                                    @if ($transaction->type === 'income')
                                        <span class="badge bg-success">Przychod</span>
                                    @else
                                        <span class="badge bg-danger">Wydatek</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->category?->name ?? '-' }}</td>
                                <td>{{ $transaction->description }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">Brak transakcji.</div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
