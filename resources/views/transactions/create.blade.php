<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nowa transakcja</title>
    @include('partials.frontend-assets')
</head>
<body class="app-shell">
    @include('partials.navbar')
    @include('partials.alerts')
    <main class="app-main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h1 class="h4 mb-2">Nowa transakcja</h1>
                            <p class="text-muted mb-0">Dodaj przychód lub wydatek z pełnym opisem i odpowiednią kategorią.</p>
                        </div>
                        <form method="POST" action="{{ route('transactions.store') }}" data-validate-form data-transaction-form novalidate>
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="transaction_date">Data</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                    <input
                                        type="date"
                                        id="transaction_date"
                                        name="transaction_date"
                                        class="form-control @error('transaction_date') is-invalid @enderror"
                                        value="{{ old('transaction_date') }}"
                                        required
                                    >
                                    @error('transaction_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Podaj datę transakcji.</div>
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
                                        min="0.01"
                                        id="amount"
                                        name="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount') }}"
                                        required
                                        data-amount-input
                                    >
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Podaj poprawną kwotę.</div>
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
                                            @checked(old('type') === 'income')
                                        >
                                        <label class="form-check-label" for="type-income">Przychód</label>
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
                                            @checked(old('type') === 'expense')
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
                                        <option value="" disabled @selected(old('category_id') === null)>Wybierz kategorię</option>
                                        @foreach ($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                data-type="{{ $category->type }}"
                                                @selected(old('category_id') == $category->id)
                                            >
                                                {{ $category->name }} ({{ $category->type === 'income' ? 'Przychód' : 'Wydatek' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Wybierz kategorię.</div>
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
                                        maxlength="255"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Opis może mieć maksymalnie 255 znaków.</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Zapisz</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
</body>
</html>

