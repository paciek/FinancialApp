<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista transakcji</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h1 class="h3 mb-0">Lista transakcji</h1>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nowa transakcja
            </a>
        </div>

        @include('partials.alerts')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('transactions.index') }}" class="row g-3" data-transaction-filter-form>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="date_from">Data od</label>
                        <input
                            type="date"
                            id="date_from"
                            name="date_from"
                            class="form-control @error('date_from') is-invalid @enderror"
                            value="{{ request('date_from') }}"
                        >
                        @error('date_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="date_to">Data do</label>
                        <input
                            type="date"
                            id="date_to"
                            name="date_to"
                            class="form-control @error('date_to') is-invalid @enderror"
                            value="{{ request('date_to') }}"
                        >
                        @error('date_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="type">Typ</label>
                        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">Wszystkie</option>
                            <option value="income" @selected(request('type') === 'income')>income</option>
                            <option value="expense" @selected(request('type') === 'expense')>expense</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="category_id">Kategoria</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Wszystkie</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-filter"></i> Filtruj
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Wyczysc</a>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-muted">Znaleziono: {{ $transactions->total() }} transakcji</p>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Kwota</th>
                                <th>Typ</th>
                                <th>Kategoria</th>
                                <th>Opis</th>
                                <th>Akcje</th>
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
                                    <td class="text-nowrap">
                                        <button type="button" class="btn btn-sm btn-outline-primary" disabled>
                                            <i class="fa-solid fa-pen"></i> Edytuj
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" disabled>
                                            <i class="fa-solid fa-trash"></i> Usun
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Brak transakcji.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
