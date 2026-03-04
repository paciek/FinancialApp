<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategorie</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
<div class="container py-4">
    @include('partials.alerts')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Kategorie</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            Nowa kategoria
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                <tr>
                    <th>Nazwa</th>
                    <th>Typ</th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>
                            <span class="badge {{ $category->type === 'income' ? 'bg-success' : 'bg-danger' }}">
                                {{ $category->type === 'income' ? 'Przychód' : 'Wydatek' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted py-4">Brak kategorii.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $categories->links() }}
    </div>
</div>
</body>
</html>

