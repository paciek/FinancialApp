<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edycja transakcji</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                @include('partials.alerts')
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-4"><i class="fa-solid fa-pen-to-square"></i> Edycja transakcji</h1>
                        <form method="POST" action="{{ route('transactions.update', $transaction) }}" data-edit-transaction-form>
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Data</label>
                                    <input
                                        type="date"
                                        name="transaction_date"
                                        value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}"
                                        class="form-control @error('transaction_date') is-invalid @enderror"
                                        required
                                    >
                                    @error('transaction_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kwota</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        name="amount"
                                        value="{{ old('amount', $transaction->amount) }}"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        required
                                    >
                                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Typ</label>
                                    <select name="type" id="typeSelect" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="income" @selected(old('type', $transaction->type) === 'income')>Przychod</option>
                                        <option value="expense" @selected(old('type', $transaction->type) === 'expense')>Wydatek</option>
                                    </select>
                                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kategoria</label>
                                    <select name="category_id" id="categorySelect" class="form-select @error('category_id') is-invalid @enderror" data-selected-category="{{ old('category_id', $transaction->category_id) }}" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ (string) old('category_id', $transaction->category_id) === (string) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Opis</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" maxlength="1000">{{ old('description', $transaction->description) }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-check"></i> Zapisz zmiany
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

