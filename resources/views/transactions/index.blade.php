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
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                @include('partials.alerts')
                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                            <h1 class="h4 m-0">Lista transakcji</h1>
                            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i>
                                Nowa transakcja
                            </a>
                        </div>

                        <div class="card border-0 bg-light-subtle mb-4">
                            <div class="card-body">
                                <form method="GET" class="row g-3 align-items-end" data-filter-form>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label" for="date_from">Od</label>
                                        <input type="date" id="date_from" name="date_from" class="form-control" value="{{ request('date_from') }}" data-filter-date-from>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label" for="date_to">Do</label>
                                        <input type="date" id="date_to" name="date_to" class="form-control" value="{{ request('date_to') }}" data-filter-date-to>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label" for="type">Typ</label>
                                        <select class="form-select" id="type" name="type">
                                            <option value="">Wszystkie</option>
                                            <option value="income" @selected(request('type') === 'income')>Przychod</option>
                                            <option value="expense" @selected(request('type') === 'expense')>Wydatek</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label" for="category_id">Kategoria</label>
                                        <select class="form-select" id="category_id" name="category_id">
                                            <option value="">Wszystkie</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected((string) $category->id === (string) request('category_id'))>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa-solid fa-filter"></i>
                                            Filtruj
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100">
                                            Wyczysc
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <p class="text-muted mb-3">Znaleziono: {{ $transactions->total() }} transakcji</p>

                        @if ($transactions->isEmpty())
                            <p class="text-muted mb-0">Brak transakcji do wyswietlenia.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
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
                                        @foreach ($transactions as $transaction)
                                            @php
                                                $typeLabel = $transaction->type === 'income' ? 'Przychod' : 'Wydatek';
                                            @endphp
                                            <tr>
                                                <td>{{ $transaction->transaction_date?->format('Y-m-d') }}</td>
                                                <td>{{ number_format((float) $transaction->amount, 2, '.', ' ') }}</td>
                                                <td>
                                                    <span class="badge {{ $transaction->type === 'income' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $typeLabel }}
                                                    </span>
                                                </td>
                                                <td>{{ $transaction->category?->name ?? '-' }}</td>
                                                <td>{{ $transaction->description ?? '-' }}</td>
                                                <td class="text-end">
                                                    <div class="d-inline-flex gap-2">
                                                        @if (Route::has('transactions.edit'))
                                                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa-solid fa-pen"></i>
                                                                Edytuj
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                                                <i class="fa-solid fa-pen"></i>
                                                                Edytuj
                                                            </button>
                                                        @endif

                                                        @if (Route::has('transactions.destroy'))
                                                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="delete">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                    Usun
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="btn btn-outline-danger btn-sm" disabled>
                                                                <i class="fa-solid fa-trash"></i>
                                                                Usun
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
