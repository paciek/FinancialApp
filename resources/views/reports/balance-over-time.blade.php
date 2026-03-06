<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saldo w czasie</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light with-right-tabs">
@include('partials.app-navbar')
<div class="container py-4">
    @include('partials.alerts')

    <h1 class="h4 mb-3">
        <i class="fa-solid fa-chart-line"></i>
        Saldo w czasie
    </h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($labels->isEmpty())
                <div class="alert alert-secondary mb-0">Brak danych do wykresu.</div>
            @else
                <div style="height: 320px;">
                    <canvas
                        id="balanceChart"
                        data-labels='@json($labels)'
                        data-values='@json($values)'
                    ></canvas>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
