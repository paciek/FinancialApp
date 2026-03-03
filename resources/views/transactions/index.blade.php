<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista transakcji</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    @php
        $createTransactionUrl = Route::has('transactions.create') ? route('transactions.create') : '#';
    @endphp
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h1 class="h3 mb-0">Lista transakcji</h1>
            <a
                href="{{ $createTransactionUrl }}"
                class="btn btn-primary {{ Route::has('transactions.create') ? '' : 'disabled' }}"
                @if (!Route::has('transactions.create')) aria-disabled="true" @endif
            >
                <i class="fa-solid fa-plus"></i> Nowa transakcja
            </a>
        </div>

        @include('partials.alerts')

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('transactions.index') }}" class="row g-3 mb-4" data-validate-form novalidate>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="type">Typ</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Wszystkie</option>
                            <option value="income" @selected(request('type') === 'income')>Przychód</option>
                            <option value="expense" @selected(request('type') === 'expense')>Wydatek</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="date_from">Data od</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="date_to">Data do</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12 col-md-2 d-grid">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="submit" class="btn btn-outline-primary">Filtruj</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Kwota</th>
                                <th>Typ</th>
                                <th>Kategoria</th>
                                <th>Opis</th>
                                <th class="text-end">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                                    <td>{{ number_format((float) $transaction->amount, 2, ',', ' ') }}</td>
                                    <td>
                                        <span class="badge {{ $transaction->type === 'income' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $transaction->type }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->category?->name ?? '-' }}</td>
                                    <td>{{ $transaction->description ?: '-' }}</td>
                                    <td class="text-end">
                                        @if (Route::has('transactions.edit'))
                                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-pen"></i> Edytuj
                                            </a>
                                        @endif

                                        @if (Route::has('transactions.destroy'))
                                            <form
                                                method="POST"
                                                action="{{ route('transactions.destroy', $transaction) }}"
                                                class="d-inline-block"
                                                data-confirm-delete
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa-solid fa-trash"></i> Usuń
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Brak transakcji do wyświetlenia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
