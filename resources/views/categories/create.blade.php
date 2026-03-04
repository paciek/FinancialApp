<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nowa kategoria</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            @include('partials.alerts')

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Nowa kategoria</h1>

                    <form method="POST" action="{{ route('categories.store') }}" data-validate-form novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nazwa</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa-solid fa-tags"></i></span>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
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
                                    <div class="invalid-feedback">Wybierz typ kategorii.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i>
                                Zapisz
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Anuluj</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

