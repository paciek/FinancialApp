<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saldo w czasie</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
@include('partials.app-navbar')
<div class="container mt-4">
    @include('partials.alerts')

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h4 class="mb-3">
                <i class="fa-solid fa-chart-line"></i>
                Saldo w czasie
            </h4>

            @if ($labels->isEmpty())
                <div class="alert alert-secondary mb-0">
                    Brak danych do wykresu.
                </div>
            @else
                <canvas
                    id="balanceChart"
                    data-labels='@json($labels)'
                    data-values='@json($values)'
                ></canvas>
            @endif
        </div>
    </div>
</div>
</body>
</html>
