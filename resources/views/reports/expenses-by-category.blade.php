<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wydatki wg kategorii</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
@include('partials.app-navbar')
<div class="container py-4">
    @include('partials.alerts')

    <h1 class="h4 mb-3">
        <i class="fa-solid fa-chart-pie"></i>
        Wydatki wg kategorii
    </h1>

    <form method="GET" class="card border-0 shadow-sm mb-3">
        <div class="card-body row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="date_from" class="form-label">Data od</label>
                <input type="date" id="date_from" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-12 col-md-4">
                <label for="date_to" class="form-label">Data do</label>
                <input type="date" id="date_to" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-12 col-md-4 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter"></i>
                    Filtruj
                </button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card bg-success-subtle shadow-sm border-0">
                <div class="card-body">
                    <h6><i class="fa-solid fa-arrow-trend-up text-success"></i> Przychody</h6>
                    <h4>{{ number_format($totalIncome, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card bg-danger-subtle shadow-sm border-0">
                <div class="card-body">
                    <h6><i class="fa-solid fa-arrow-trend-down text-danger"></i> Wydatki</h6>
                    <h4>{{ number_format($totalExpense, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 {{ $balance >= 0 ? 'bg-primary-subtle' : 'bg-warning-subtle' }}">
                <div class="card-body">
                    <h6><i class="fa-solid fa-scale-balanced"></i> Saldo</h6>
                    <h4 class="{{ $balance < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($balance, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Laczna suma wydatkow</span>
                <strong>{{ number_format($total, 2) }}</strong>
            </div>
            @if ($chartData->isEmpty())
                <div class="alert alert-secondary mb-0">Brak danych do wykresu dla wybranego zakresu.</div>
            @else
                <div class="mx-auto" style="max-width: 360px;">
                    <canvas id="expenseChart" height="220" data-labels='@json($chartLabels)' data-values='@json($chartData)'></canvas>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
