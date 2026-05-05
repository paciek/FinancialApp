<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista transakcji</title>
    @include('partials.frontend-assets')
</head>
<body class="app-shell">
    @include('partials.navbar')
    @include('partials.alerts')

    <main class="app-main">
    <div class="container-xxl">
        <div class="page-header">
            <div>
                <span class="page-header__eyebrow">
                    <i class="fa-solid fa-receipt"></i>
                    Rejestr finansów
                </span>
                <h1 class="page-header__title">Transakcje</h1>
                <p class="page-header__description">Przeglądaj, filtruj i zarządzaj wpisami w wygodnym panelu administracyjnym.</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Nowa transakcja
            </a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card form-card form-card--wide">
                    <div class="card-body p-4 p-md-5">
                        <div class="section-card__header">
                            <div>
                                <h2 class="section-card__title">Lista transakcji</h2>
                                <p class="section-card__description">Filtruj wyniki, sortuj dane i szybko przechodź do edycji.</p>
                            </div>
                        </div>

                        <div class="card toolbar-card border-0 mb-4">
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
                                            <option value="income" @selected(request('type') === 'income')>Przychód</option>
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
                                            Wyczyść
                                        </a>
                                    </div>
                                    @if (request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                </form>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('transactions.index') }}" class="mb-3">
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Szukaj po opisie..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-search"></i>
                                    Szukaj
                                </button>
                            </div>
                            @foreach (request()->except(['search', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                        </form>

                        <p class="text-muted mb-3">Znaleziono: {{ $transactions->total() }} transakcji</p>

                        @if ($transactions->isEmpty())
                            <p class="text-muted mb-0">Brak transakcji spełniających kryteria.</p>
                        @else
                            @php
                                $currentSort = request('sort', 'transaction_date');
                                $currentDirection = request('direction', 'desc');
                                $nextDateDirection = $currentSort === 'transaction_date' && $currentDirection === 'asc' ? 'desc' : 'asc';
                                $nextAmountDirection = $currentSort === 'amount' && $currentDirection === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <div class="table-responsive">
                                <table class="table data-table align-middle">
                                    <thead>
                                        <tr>
                                            <th>
                                                <a href="{{ route('transactions.index', array_merge(request()->all(), ['sort' => 'transaction_date', 'direction' => $nextDateDirection])) }}" class="text-decoration-none {{ $currentSort === 'transaction_date' ? 'text-primary' : 'text-body' }}">
                                                    Data
                                                    @if ($currentSort === 'transaction_date')
                                                        <i class="fa-solid {{ $currentDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                                    @else
                                                        <i class="fa-solid fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                <a href="{{ route('transactions.index', array_merge(request()->all(), ['sort' => 'amount', 'direction' => $nextAmountDirection])) }}" class="text-decoration-none {{ $currentSort === 'amount' ? 'text-primary' : 'text-body' }}">
                                                    Kwota
                                                    @if ($currentSort === 'amount')
                                                        <i class="fa-solid {{ $currentDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                                    @else
                                                        <i class="fa-solid fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Typ</th>
                                            <th>Kategoria</th>
                                            <th>Opis</th>
                                            <th class="text-end">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            @php
                                                $typeLabel = $transaction->type === 'income' ? 'Przychód' : 'Wydatek';
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
                                                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Czy na pewno chcesz usunąć tę transakcję?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                    Usuń
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="btn btn-outline-danger btn-sm" disabled>
                                                                <i class="fa-solid fa-trash"></i>
                                                                Usuń
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
    </main>
</body>
</html>





