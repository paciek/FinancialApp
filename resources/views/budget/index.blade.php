<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Budzet miesieczny</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    @include('partials.navbar')
    @include('partials.alerts')

    @php
        $progressClass = 'bg-success';
        if ($percentage_used >= 90) {
            $progressClass = 'bg-danger';
        } elseif ($percentage_used >= 60) {
            $progressClass = 'bg-warning';
        }
    @endphp

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 mb-3">
                            <i class="fa-solid fa-wallet"></i>
                            Budzet miesieczny
                        </h1>
                        <p class="text-muted mb-4">Miesiac: {{ $current_month }}/{{ $current_year }}</p>

                        <form method="POST" action="{{ route('budget.store') }}">
                            @csrf
                            <label class="form-label" for="limit_amount">Limit miesieczny</label>
                            <input
                                type="number"
                                id="limit_amount"
                                name="limit_amount"
                                class="form-control @error('limit_amount') is-invalid @enderror"
                                placeholder="Podaj limit miesieczny"
                                value="{{ old('limit_amount', $budget?->limit_amount) }}"
                                min="0"
                                step="0.01"
                                required
                            >
                            @error('limit_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <button class="btn btn-primary mt-3" type="submit">
                                <i class="fa-solid fa-wallet"></i>
                                Zapisz budzet
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">Limit</div>
                                    <div class="h5 mb-0">{{ number_format((float) ($budget->limit_amount ?? 0), 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">
                                        <i class="fa-solid fa-arrow-down"></i>
                                        Wydano
                                    </div>
                                    <div class="h5 mb-0 text-danger">{{ number_format((float) $spent_amount, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted text-uppercase small">
                                        <i class="fa-solid fa-coins"></i>
                                        Pozostalo
                                    </div>
                                    <div class="h5 mb-0 text-success">{{ number_format((float) $remaining_amount, 2, '.', ' ') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted text-uppercase small">Wykorzystanie budzetu</span>
                                <span class="fw-semibold">{{ $percentage_used }}%</span>
                            </div>
                            <div class="progress">
                                <div
                                    class="progress-bar {{ $progressClass }}"
                                    role="progressbar"
                                    style="width: {{ $percentage_used }}%;"
                                    aria-valuenow="{{ $percentage_used }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                >
                                    {{ $percentage_used }}%
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
