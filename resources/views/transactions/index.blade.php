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

    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="h5 mb-3">Nowa transakcja</h1>
                    @if ($categories->isEmpty())
                        <div class="alert alert-warning mb-0">
                            Najpierw dodaj kategorie w sekcji Kategorie.
                        </div>
                    @else
                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="transaction_date">Data</label>
                            <input
                                type="date"
                                id="transaction_date"
                                name="transaction_date"
                                class="form-control @error('transaction_date') is-invalid @enderror"
                                value="{{ old('transaction_date', now()->toDateString()) }}"
                                required
                            >
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="type">Typ</label>
                            <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="expense" @selected(old('type') === 'expense')>Wydatek</option>
                                <option value="income" @selected(old('type') === 'income')>Przychod</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="category_id">Kategoria</label>
                            <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) old('category_id') === (int) $category->id)>
                                        {{ $category->name }} ({{ $category->type === 'income' ? 'Przychod' : 'Wydatek' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="amount">Kwota</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                id="amount"
                                name="amount"
                                class="form-control @error('amount') is-invalid @enderror"
                                value="{{ old('amount') }}"
                                required
                            >
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Opis</label>
                            <input
                                type="text"
                                id="description"
                                name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                value="{{ old('description') }}"
                                maxlength="255"
                            >
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-plus"></i>
                            Dodaj
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Historia transakcji</h2>
                    <form method="GET" action="{{ route('transactions.index') }}" class="row g-2 mb-3">
                        <div class="col-12 col-md-3">
                            <label for="date_from" class="form-label">Data od</label>
                            <input
                                type="date"
                                id="date_from"
                                name="date_from"
                                class="form-control"
                                value="{{ $filters['date_from'] ?? '' }}"
                            >
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="date_to" class="form-label">Data do</label>
                            <input
                                type="date"
                                id="date_to"
                                name="date_to"
                                class="form-control"
                                value="{{ $filters['date_to'] ?? '' }}"
                            >
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="filter_type" class="form-label">Typ</label>
                            <select id="filter_type" name="type" class="form-select">
                                <option value="">Wszystkie</option>
                                <option value="expense" @selected(($filters['type'] ?? '') === 'expense')>Wydatek</option>
                                <option value="income" @selected(($filters['type'] ?? '') === 'income')>Przychod</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="filter_category_id" class="form-label">Kategoria</label>
                            <select id="filter_category_id" name="category_id" class="form-select">
                                <option value="">Wszystkie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) ($filters['category_id'] ?? 0) === (int) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <label for="sort_by" class="form-label">Sortuj po</label>
                            <select id="sort_by" name="sort_by" class="form-select">
                                <option value="transaction_date" @selected(($filters['sort_by'] ?? 'transaction_date') === 'transaction_date')>Data</option>
                                <option value="amount" @selected(($filters['sort_by'] ?? '') === 'amount')>Kwota</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <label for="sort_dir" class="form-label">Kierunek</label>
                            <select id="sort_dir" name="sort_dir" class="form-select">
                                <option value="desc" @selected(($filters['sort_dir'] ?? 'desc') === 'desc')>Malejaco</option>
                                <option value="asc" @selected(($filters['sort_dir'] ?? '') === 'asc')>Rosnaco</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-filter"></i>
                                Filtruj
                            </button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>

                    @if ($transactions->isEmpty())
                        <div class="alert alert-secondary mb-0">Brak transakcji.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Typ</th>
                                    <th>Kategoria</th>
                                    <th>Kwota</th>
                                    <th>Opis</th>
                                    <th class="text-end">Akcje</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge {{ $transaction->type === 'income' ? 'text-bg-success' : 'text-bg-danger' }}">
                                                {{ $transaction->type === 'income' ? 'Przychod' : 'Wydatek' }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->category?->name ?? 'Brak' }}</td>
                                        <td>{{ number_format((float) $transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td class="text-end">
                                            <button
                                                class="btn btn-outline-primary btn-sm"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#edit-{{ $transaction->id }}"
                                            >
                                                Edytuj
                                            </button>
                                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Usun</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="edit-{{ $transaction->id }}">
                                        <td colspan="6">
                                            <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="row g-2">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-12 col-md-2">
                                                    <input type="date" name="transaction_date" class="form-control" value="{{ $transaction->transaction_date->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <select name="type" class="form-select" required>
                                                        <option value="expense" @selected($transaction->type === 'expense')>Wydatek</option>
                                                        <option value="income" @selected($transaction->type === 'income')>Przychod</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <select name="category_id" class="form-select" required>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @selected((int) $transaction->category_id === (int) $category->id)>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ number_format((float) $transaction->amount, 2, '.', '') }}" required>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <input type="text" name="description" class="form-control" value="{{ $transaction->description }}" maxlength="255">
                                                </div>
                                                <div class="col-12 col-md-1 d-grid">
                                                    <button type="submit" class="btn btn-primary btn-sm">OK</button>
                                                </div>
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
    </div>
</div>
</body>
</html>
