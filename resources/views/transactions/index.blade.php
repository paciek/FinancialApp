<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transakcje</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
@include('partials.app-navbar')
<div class="container py-4">
    @include('partials.alerts')

    <h1 class="h4 mb-3">
        <i class="fa-solid fa-receipt"></i>
        Transakcje
    </h1>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header">
            <i class="fa-solid fa-plus"></i>
            Nowa transakcja
        </div>
        <div class="card-body">
            @if ($categories->isEmpty())
                <div class="alert alert-warning mb-0">Najpierw dodaj kategorie.</div>
            @else
                <form method="POST" action="{{ route('transactions.store') }}" class="row g-2">
                    @csrf
                    <div class="col-md-2">
                        <input type="date" name="transaction_date" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select" required>
                            <option value="income">Przychod</option>
                            <option value="expense">Wydatek</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="Kwota" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="description" class="form-control" placeholder="Opis" maxlength="255">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm">Dodaj</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header">
            <i class="fa-solid fa-filter"></i>
            Filtry
        </div>
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Data od</label>
                    <input id="date_from" type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Data do</label>
                    <input id="date_to" type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Typ</label>
                    <select id="type" name="type" class="form-select">
                        <option value="">Wszystkie</option>
                        <option value="income" @selected(($filters['type'] ?? '') === 'income')>Przychod</option>
                        <option value="expense" @selected(($filters['type'] ?? '') === 'expense')>Wydatek</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="category_id" class="form-label">Kategoria</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">Wszystkie</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) ($filters['category_id'] ?? 0) === (int) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">Filtruj</button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h5 mb-3">Lista transakcji</h2>
            @if ($transactions->isEmpty())
                <div class="alert alert-secondary mb-0">Brak transakcji.</div>
            @else
                @php
                    $currentSort = request('sort', 'date');
                    $currentDirection = request('direction', 'desc');
                    $dateDirection = $currentSort === 'date' && $currentDirection === 'asc' ? 'desc' : 'asc';
                    $amountDirection = $currentSort === 'amount' && $currentDirection === 'asc' ? 'desc' : 'asc';
                @endphp
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                        <tr>
                            <th>
                                <a class="text-decoration-none text-body" href="{{ route('transactions.index', array_merge(request()->query(), ['sort' => 'date', 'direction' => $dateDirection])) }}">
                                    Data
                                    @if ($currentSort === 'date')
                                        @if ($currentDirection === 'asc')
                                            <i class="fa-solid fa-arrow-up"></i>
                                        @else
                                            <i class="fa-solid fa-arrow-down"></i>
                                        @endif
                                    @else
                                        <i class="fa-solid fa-arrow-down text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Typ</th>
                            <th>Kategoria</th>
                            <th>
                                <a class="text-decoration-none text-body" href="{{ route('transactions.index', array_merge(request()->query(), ['sort' => 'amount', 'direction' => $amountDirection])) }}">
                                    Kwota
                                    @if ($currentSort === 'amount')
                                        @if ($currentDirection === 'asc')
                                            <i class="fa-solid fa-arrow-up"></i>
                                        @else
                                            <i class="fa-solid fa-arrow-down"></i>
                                        @endif
                                    @else
                                        <i class="fa-solid fa-arrow-down text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Opis</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                                <td>{{ $transaction->type === 'income' ? 'Przychod' : 'Wydatek' }}</td>
                                <td>{{ $transaction->category?->name ?? '-' }}</td>
                                <td>{{ number_format((float) $transaction->amount, 2) }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Usun</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
