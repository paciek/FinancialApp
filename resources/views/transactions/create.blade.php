<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nowa transakcja</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            @include('partials.alerts')

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Nowa transakcja</h1>

                    <form method="POST" action="{{ route('transactions.store') }}" data-validate-form novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Data</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
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
                                    <div class="invalid-feedback">Podaj datê transakcji.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Kwota</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa-solid fa-money-bill-wave"></i></span>
                                <input
                                    type="number"
                                    id="amount"
                                    name="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}"
                                    min="0.01"
                                    step="0.01"
                                    required
                                >
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Podaj poprawn¹ kwotê wiêksz¹ od zera.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Typ</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa-solid fa-scale-balanced"></i></span>
                                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Wybierz typ</option>
                                    <option value="income" @selected(old('type') === 'income')>Przychód</option>
                                    <option value="expense" @selected(old('type') === 'expense')>Wydatek</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Wybierz typ transakcji.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategoria</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa-solid fa-tags"></i></span>
                                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Wybierz kategoriê</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" data-type="{{ $category->type }}" @selected((string) old('category_id') === (string) $category->id)>
                                            {{ $category->name }} ({{ $category->type }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Wybierz kategoriê.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Opis</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa-solid fa-align-left"></i></span>
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
                                    <div class="invalid-feedback">Opis nie mo¿e mieæ wiêcej ni¿ 255 znaków.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i>
                                Dodaj transakcjê
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
