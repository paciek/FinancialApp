<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Podsumowanie finansowe</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    @include('partials.navbar')
    <div class="container py-5">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="h6 text-uppercase text-muted">Suma przychodow</h2>
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
                                <h2 class="h6 text-uppercase text-muted">Suma wydatkow</h2>
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

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h6 text-uppercase text-muted mb-3">
                            <i class="fa-solid fa-chart-pie"></i>
                            Wydatki wedlug kategorii
                        </h2>
                        <canvas id="expensesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('expensesChart');

        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        data: @json($chartData),
                        backgroundColor: @json($chartColors),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
