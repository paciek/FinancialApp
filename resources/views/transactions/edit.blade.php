<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edycja transakcji</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            @include('partials.alerts')

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4"><i class="fa-solid fa-pen-to-square"></i> Edycja transakcji</h1>

                    <form method="POST" action="{{ route('transactions.update', $transaction) }}" data-validate-form novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Data</label>
                            <input type="date" id="transaction_date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', $transaction->transaction_date?->format('Y-m-d')) }}" required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Kwota</label>
                            <input type="number" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $transaction->amount) }}" min="0.01" step="0.01" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Typ</label>
                            <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="income" @selected(old('type', $transaction->type) === 'income')>Przychód</option>
                                <option value="expense" @selected(old('type', $transaction->type) === 'expense')>Wydatek</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategoria</label>
                            <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) old('category_id', $transaction->category_id) === $category->id)>
                                        {{ $category->name }} ({{ $category->type === 'income' ? 'Przychód' : 'Wydatek' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Opis</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" maxlength="255">{{ old('description', $transaction->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i>
                                Zapisz zmiany
                            </button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Anuluj</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

