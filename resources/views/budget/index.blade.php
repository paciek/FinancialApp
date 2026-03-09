<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Budzet miesieczny</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light with-right-tabs">
    @include('partials.app-navbar')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 fade-in">
                @include('partials.alerts')

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="fs-4 fw-bold mb-4">
                            <i class="fa-solid fa-wallet me-2"></i>
                            Budzet miesieczny
                        </h1>

                        <p class="mb-1">
                            <strong>Aktualny limit:</strong>
                            {{ number_format($limitAmount, 2, '.', ' ') }} PLN
                        </p>
                        <p class="mb-1">
                            <strong>Miesiac:</strong>
                            {{ $month }}
                        </p>
                        <p class="mb-3">
                            <strong>Budzet na ten miesiac:</strong>
                            {{ number_format($limitAmount, 2, '.', ' ') }} PLN
                        </p>

                        <p class="mb-2">
                            <strong>Wydano / limit:</strong>
                            {{ number_format($spentAmount, 2, '.', ' ') }} / {{ number_format($limitAmount, 2, '.', ' ') }} PLN
                        </p>

                        <div class="progress mb-4" role="progressbar" aria-label="Postep budzetu" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ (int) round($progressPercent) }}">
                            <div
                                class="progress-bar {{ $progressPercent >= 100 ? 'bg-danger' : 'bg-warning' }}"
                                style="width: {{ number_format($progressPercent, 2, '.', '') }}%;"
                            >
                                {{ number_format($progressPercent, 0) }}%
                            </div>
                        </div>

                        <form method="POST" action="{{ $budget ? route('budget.update') : route('budget.store') }}" data-validate-form novalidate>
                            @csrf
                            @if ($budget)
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label" for="limit_amount">Limit wydatkow</label>
                                <input
                                    id="limit_amount"
                                    type="number"
                                    step="0.01"
                                    class="form-control @error('limit_amount') is-invalid @enderror"
                                    name="limit_amount"
                                    value="{{ old('limit_amount', $budget?->limit_amount) }}"
                                    required
                                    min="0"
                                >
                                @error('limit_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Zapisz budzet</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
