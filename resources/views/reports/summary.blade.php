<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Podsumowanie finansowe</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light report-page">
    @include('partials.navbar')
    
    @include('partials.alerts')
    <div class="container pt-3 pb-5">
        <div class="mb-4">
            <h1 class="h4 fw-semibold mb-1">Podsumowanie finansowe</h1>
            <p class="text-muted mb-0">Szybki wgląd w przychody, wydatki i saldo.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="h6 text-uppercase text-muted">Suma przychodów</h2>
                                <div class="h4 mb-0 text-success">{{ $income }}</div>
                            </div>
                            <i class="fa-solid fa-arrow-up fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="h6 text-uppercase text-muted">Suma wydatków</h2>
                                <div class="h4 mb-0 text-danger">{{ $expenses }}</div>
                            </div>
                            <i class="fa-solid fa-arrow-down fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
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

        <div class="row g-4 mt-1">
            <div class="col-lg-5">
                <div class="card shadow-sm h-100 border-0 chart-card chart-card--donut">
                    <div class="card-body">
                        <h2 class="chart-title mb-3">
                            <i class="fa-solid fa-chart-pie"></i>
                            Wydatki według kategorii
                        </h2>
                        <div class="chart-area">
                            <canvas id="expensesChart" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 chart-card chart-card--line h-100">
                    <div class="card-body">
                        <h2 class="chart-title mb-3">
                            <i class="fa-solid fa-chart-line"></i>
                            Saldo w czasie
                        </h2>
                        <div class="chart-area">
                            <canvas id="balanceChart" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('expensesChart');

        if (ctx) {
            const labels = @json($chartLabels);
            const data = @json($chartData);
            const colors = @json($chartColors);
            const uniqueColors = Array.isArray(colors) ? new Set(colors.filter(Boolean)) : new Set();
            const fallbackColors = labels.map((_, index) => `hsl(${(index * 47) % 360} 65% 55%)`);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: uniqueColors.size > 1 ? colors : fallbackColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        const balanceCtx = document.getElementById('balanceChart');

        if (balanceCtx) {
            new Chart(balanceCtx, {
                type: 'line',
                data: {
                    labels: @json($balanceLabels),
                    datasets: [{
                        label: 'Saldo',
                        data: @json($balanceData),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13,110,253,0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>







