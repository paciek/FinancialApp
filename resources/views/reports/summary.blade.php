<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Podsumowanie finansowe</title>
    @include('partials.frontend-assets')
</head>
<body class="app-shell report-page">
    @include('partials.navbar')
    @include('partials.alerts')
    <main class="app-main">
    <div class="container-xxl">
        <div class="page-header">
            <div>
                <span class="page-header__eyebrow">
                    <i class="fa-solid fa-chart-line"></i>
                    Analiza finansowa
                </span>
                <h1 class="page-header__title">Podsumowanie finansowe</h1>
                <p class="page-header__description">Wykresy i wskaźniki, które pokazują kierunek Twoich finansów.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card surface-card border-0 h-100">
                    <div class="card-body stats-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stats-card__label">Suma przychodów</p>
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
                                <p class="stats-card__label">Suma wydatków</p>
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

        <div class="row g-4 mt-1">
            <div class="col-lg-5">
                <div class="card chart-card chart-card--donut surface-card border-0 h-100">
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
                <div class="card chart-card chart-card--line surface-card border-0 h-100">
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
    </main>

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






