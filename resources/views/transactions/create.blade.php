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

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-4">Nowa transakcja</h1>

                        <form method="POST" action="{{ route('transactions.store') }}">
                            @csrf

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="transaction_date">Data</label>
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
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="amount">Kwota</label>
                                    <input
                                        type="number"
                                        id="amount"
                                        name="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount') }}"
                                        step="0.01"
                                        min="0.01"
                                        required
                                    >
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="type">Typ</label>
                                    <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">Wybierz typ</option>
                                        <option value="income" @selected(old('type') === 'income')>income</option>
                                        <option value="expense" @selected(old('type') === 'expense')>expense</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="category_id">Kategoria</label>
                                    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">Wybierz kategorie</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>
                                                {{ $category->name }} ({{ $category->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="description">Opis (opcjonalnie)</label>
                                    <textarea
                                        id="description"
                                        name="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="3"
                                        maxlength="255"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk"></i> Zapisz
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

