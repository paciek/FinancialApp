<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista transakcji</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
<div class="container py-4">
    @include('partials.alerts')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h4 mb-0">Lista transakcji</h1>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            Nowa transakcja
        </a>
    </div>

    <form method="GET" class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Szukaj po opisie..." value="{{ request('q') }}" maxlength="255">
                @if (request()->filled('date_from'))
                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                @endif
                @if (request()->filled('date_to'))
                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                @endif
                @if (request()->filled('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                @if (request()->filled('category_id'))
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif
                @if (request()->filled('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if (request()->filled('direction'))
                    <input type="hidden" name="direction" value="{{ request('direction') }}">
                @endif
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </form>

    <form method="GET" class="card border-0 shadow-sm mb-3">
        <div class="card-body row g-3 align-items-end">
            <input type="hidden" name="q" value="{{ request('q') }}">

            <div class="col-12 col-md-3">
                <label for="date_from" class="form-label">Data od</label>
                <input type="date" id="date_from" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            <div class="col-12 col-md-3">
                <label for="date_to" class="form-label">Data do</label>
                <input type="date" id="date_to" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>

            <div class="col-12 col-md-2">
                <label for="type" class="form-label">Typ</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Wszystkie</option>
                    <option value="income" @selected(request('type') === 'income')>income</option>
                    <option value="expense" @selected(request('type') === 'expense')>expense</option>
                </select>
            </div>

            <div class="col-12 col-md-2">
                <label for="category_id" class="form-label">Kategoria</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Wszystkie</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-filter"></i>
                    Filtruj
                </button>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100">Wyczyść</a>
            </div>
        </div>
    </form>

    @if(request('q'))
        <div class="alert alert-info">
            Wyniki dla: <strong>{{ request('q') }}</strong>
            ({{ $transactions->total() }} znalezionych)
            <a href="{{ route('transactions.index', request()->except('q', 'page')) }}" class="btn btn-sm btn-outline-secondary ms-2">Wyczyść wyszukiwanie</a>
        </div>
    @endif

    <p class="text-muted">Znaleziono: {{ $transactions->total() }} transakcji</p>

    @php
        $currentSort = request('sort', 'transaction_date');
        $currentDirection = request('direction', 'desc');

        $nextDirectionForDate = $currentSort === 'transaction_date' && $currentDirection === 'asc' ? 'desc' : 'asc';
        $nextDirectionForAmount = $currentSort === 'amount' && $currentDirection === 'asc' ? 'desc' : 'asc';

        $iconForDate = $currentSort !== 'transaction_date'
            ? 'fa-sort'
            : ($currentDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down');

        $iconForAmount = $currentSort !== 'amount'
            ? 'fa-sort'
            : ($currentDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
    @endphp

    <div class="table-responsive card border-0 shadow-sm">
        <table class="table mb-0 align-middle">
            <thead>
            <tr>
                <th>
                    <a class="text-decoration-none {{ $currentSort === 'transaction_date' ? 'text-primary' : 'text-dark' }}"
                       href="{{ route('transactions.index', array_merge(request()->all(), ['sort' => 'transaction_date', 'direction' => $nextDirectionForDate, 'page' => 1])) }}">
                        Data <i class="fa-solid {{ $iconForDate }}"></i>
                    </a>
                </th>
                <th>
                    <a class="text-decoration-none {{ $currentSort === 'amount' ? 'text-primary' : 'text-dark' }}"
                       href="{{ route('transactions.index', array_merge(request()->all(), ['sort' => 'amount', 'direction' => $nextDirectionForAmount, 'page' => 1])) }}">
                        Kwota <i class="fa-solid {{ $iconForAmount }}"></i>
                    </a>
                </th>
                <th>Typ</th>
                <th>Kategoria</th>
                <th>Opis</th>
                <th>Akcje</th>
            </tr>
            </thead>
            <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date?->format('Y-m-d') }}</td>
                    <td>{{ number_format((float) $transaction->amount, 2, ',', ' ') }}</td>
                    <td>
                        <span class="badge {{ $transaction->type === 'income' ? 'bg-success' : 'bg-danger' }}">{{ $transaction->type }}</span>
                    </td>
                    <td>{{ $transaction->category?->name }}</td>
                    <td>{{ $transaction->description ?: '-' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" data-delete-form>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
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

    <div class="mt-3">
        {{ $transactions->links() }}
    </div>
</div>
</body>
</html>
