<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategorie</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                @include('partials.alerts')
                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                            <h1 class="h4 m-0">Twoje kategorie</h1>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                Dodaj kategorie
                            </a>
                        </div>

                        @if ($categories->isEmpty())
                            <p class="text-muted mb-0">Brak kategorii. Dodaj pierwsza.</p>
                        @else
                            <div class="list-group">
                                @foreach ($categories as $category)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $category->name }}</span>
                                        <span class="badge category-badge {{ $category->type === 'income' ? 'category-badge--income' : 'category-badge--expense' }}">
                                            {{ $category->type }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>