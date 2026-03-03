<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nowa transakcja</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                @include('partials.alerts')

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-4">Nowa transakcja</h1>

                        <form method="POST" action="{{ route('transactions.store') }}" data-validate-form data-transaction-form novalidate>
                            @csrf

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="transaction_date">Data</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                                        <input
                                            type="date"
                                            id="transaction_date"
                                            name="transaction_date"
                                            class="form-control @error('transaction_date') is-invalid @enderror"
                                            value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                                            required
                                        >
                                        @error('transaction_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Wybierz date transakcji.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="amount">Kwota</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-money-bill-wave"></i></span>
                                        <input
                                            type="number"
                                            id="amount"
                                            name="amount"
                                            step="0.01"
                                            min="0.01"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            value="{{ old('amount') }}"
                                            required
                                            data-amount-field
                                        >
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Podaj poprawna kwote.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="type">Typ</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-scale-balanced"></i></span>
                                        <select
                                            id="type"
                                            name="type"
                                            class="form-select @error('type') is-invalid @enderror"
                                            required
                                            data-transaction-type
                                        >
                                            <option value="">Wybierz typ</option>
                                            <option value="income" @selected(old('type') === 'income')>Przychod</option>
                                            <option value="expense" @selected(old('type') === 'expense')>Wydatek</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Wybierz typ transakcji.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="category_id">Kategoria</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-tags"></i></span>
                                        <select
                                            id="category_id"
                                            name="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror"
                                            required
                                            data-category-select
                                        >
                                            <option value="">Wybierz kategorie</option>
                                            @foreach ($categories as $category)
                                                <option
                                                    value="{{ $category->id }}"
                                                    data-type="{{ $category->type }}"
                                                    @selected((string) old('category_id') === (string) $category->id)
                                                >
                                                    {{ $category->name }} ({{ $category->type }})
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

                                <div class="col-12">
                                    <label class="form-label" for="description">Opis (opcjonalnie)</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-pen"></i></span>
                                        <textarea
                                            id="description"
                                            name="description"
                                            rows="3"
                                            maxlength="255"
                                            class="form-control @error('description') is-invalid @enderror"
                                        >{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Opis moze miec maksymalnie 255 znakow.</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk"></i> Zapisz transakcje
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

