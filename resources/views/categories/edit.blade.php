<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edytuj kategorię</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="container py-5">
        @include('partials.alerts')

        <div class="card category-form-card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h4 mb-4 text-center">Edycja kategorii</h1>

                <form method="POST" action="{{ route('categories.update', $category) }}" data-validate-form novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label" for="name">Nazwa</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $category->name) }}"
                                required
                                maxlength="100"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Podaj nazwę kategorii.</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Typ</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input @error('type') is-invalid @enderror"
                                    type="radio"
                                    name="type"
                                    id="type_income"
                                    value="income"
                                    {{ old('type', $category->type) === 'income' ? 'checked' : '' }}
                                    required
                                >
                                <label class="form-check-label" for="type_income">
                                    <span class="badge type-badge type-badge--income">Przychód</span>
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input @error('type') is-invalid @enderror"
                                    type="radio"
                                    name="type"
                                    id="type_expense"
                                    value="expense"
                                    {{ old('type', $category->type) === 'expense' ? 'checked' : '' }}
                                    required
                                >
                                <label class="form-check-label" for="type_expense">
                                    <span class="badge type-badge type-badge--expense">Wydatek</span>
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback d-block">Wybierz typ kategorii.</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Wróć do listy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
