<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategorie</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light has-fixed-navbar">
    @include('partials.navbar')
    
    @include('partials.alerts')
<div class="container pt-3 pb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                            <div>
                                <h1 class="h4 m-0">Twoje kategorie</h1>
                                <p class="text-muted mb-0">Zorganizuj transakcje według typów.</p>
                            </div>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                Dodaj kategorię
                            </a>
                        </div>

                        @if ($categories->isEmpty())
                            <p class="text-muted mb-0">Brak kategorii. Dodaj pierwszą.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nazwa</th>
                                            <th>Typ</th>
                                            <th class="text-end">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            @php
                                                $typeLabel = $category->type === 'income' ? 'Przychód' : 'Wydatek';
                                            @endphp
                                            <tr>
                                                <td>{{ $category->name }}</td>
                                                <td>
                                                    <span class="badge category-badge {{ $category->type === 'income' ? 'category-badge--income' : 'category-badge--expense' }}">
                                                        {{ $typeLabel }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-inline-flex gap-2">
                                                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary btn-sm">
                                                            <i class="fa-solid fa-pen"></i>
                                                            Edytuj
                                                        </a>
                                                        <form method="POST" action="{{ route('categories.destroy', $category) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="delete">
                                                                <i class="fa-solid fa-trash"></i>
                                                                Usun
                                                            </button>
                                                        </form>
                                                    </div>
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
        </div>
    </div>
</body>
</html>










