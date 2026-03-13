<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edycja kategorii</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                @include('partials.alerts')
                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 text-center mb-4">Edycja kategorii</h1>
                        <form method="POST" action="{{ route('categories.update', $category) }}" data-validate-form novalidate>
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
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
                                        <div class="invalid-feedback">Podaj nazwe kategorii.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
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
                                            @checked(old('type', $category->type) === 'income')
                                        >
                                        <label class="form-check-label" for="type-income">
                                            Przychod <span class="badge category-badge category-badge--income">Przychod</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input @error('type') is-invalid @enderror"
                                            type="radio"
                                            name="type"
                                            id="type-expense"
                                            value="expense"
                                            required
                                            @checked(old('type', $category->type) === 'expense')
                                        >
                                        <label class="form-check-label" for="type-expense">
                                            Wydatek <span class="badge category-badge category-badge--expense">Wydatek</span>
                                        </label>
                                    </div>
                                </div>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback d-block">Wybierz typ kategorii.</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Zapisz zmiany</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
