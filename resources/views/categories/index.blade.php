<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategorie</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
@include('partials.app-navbar')
<div class="container py-4">
    @include('partials.alerts')

    <h1 class="h4 mb-3">
        <i class="fa-solid fa-layer-group"></i>
        Kategorie
    </h1>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="POST" action="{{ route('categories.store') }}" class="row g-2">
                @csrf
                <div class="col-md-5">
                    <input type="text" name="name" class="form-control" placeholder="Nazwa kategorii" required maxlength="100">
                </div>
                <div class="col-md-4">
                    <select name="type" class="form-select" required>
                        <option value="income">Przychod</option>
                        <option value="expense">Wydatek</option>
                    </select>
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($categories->isEmpty())
                <div class="alert alert-secondary mb-0">Brak kategorii.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>Typ</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->type === 'income' ? 'Przychod' : 'Wydatek' }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Usun</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
