<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edycja transakcji</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    @include('partials.navbar')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                @include('partials.alerts')
                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 text-center mb-4">
                            <i class="fa-solid fa-pen"></i>
                            Edycja transakcji
                        </h1>
                        <form method="POST" action="{{ route('transactions.update', $transaction) }}" data-validate-form data-transaction-form novalidate>
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label" for="transaction_date">Data</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                    <input
                                        type="date"
                                        id="transaction_date"
                                        name="transaction_date"
                                        class="form-control @error('transaction_date') is-invalid @enderror"
                                        value="{{ old('transaction_date', $transaction->transaction_date?->format('Y-m-d')) }}"
                                        required
                                    >
                                    @error('transaction_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Podaj date transakcji.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="amount">Kwota</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-money-bill"></i></span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        id="amount"
                                        name="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount', $transaction->amount) }}"
                                        required
                                        data-amount-input
                                    >
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Podaj poprawna kwote.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Typ</label>
                                <div class="d-flex flex-column gap-2">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input @error('type') is-invalid @enderror"
                                            type="radio"
                                            name="type"
                                            id="type-income"
                                            value="income"
                                            required
                                            data-type-option
                                            @checked(old('type', $transaction->type) === 'income')
                                        >
                                        <label class="form-check-label" for="type-income">Przychod</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input @error('type') is-invalid @enderror"
                                            type="radio"
                                            name="type"
                                            id="type-expense"
                                            value="expense"
                                            required
                                            data-type-option
                                            @checked(old('type', $transaction->type) === 'expense')
                                        >
                                        <label class="form-check-label" for="type-expense">Wydatek</label>
                                    </div>
                                </div>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback d-block">Wybierz typ transakcji.</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="category_id">Kategoria</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-layer-group"></i></span>
                                    <select
                                        id="category_id"
                                        name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror"
                                        required
                                        data-category-select
                                    >
                                        <option value="" disabled @selected(old('category_id', $transaction->category_id) === null)>Wybierz kategorie</option>
                                        @foreach ($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                data-type="{{ $category->type }}"
                                                @selected((string) old('category_id', $transaction->category_id) === (string) $category->id)
                                            >
                                                {{ $category->name }} ({{ $category->type === 'income' ? 'Przychod' : 'Wydatek' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Wybierz kategorie.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="description">Opis (opcjonalnie)</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-pen"></i></span>
                                    <textarea
                                        id="description"
                                        name="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="3"
                                        maxlength="500"
                                    >{{ old('description', $transaction->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Opis moze miec maksymalnie 500 znakow.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save"></i>
                                    Zapisz zmiany
                                </button>
                                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                                    Anuluj
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

