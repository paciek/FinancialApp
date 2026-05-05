<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategorie</title>
    @include('partials.frontend-assets')
</head>
<body class="app-shell">
    @include('partials.navbar')
    @include('partials.alerts')
    <main class="app-main">
    <div class="container">
        <div class="page-header">
            <div>
                <span class="page-header__eyebrow">
                    <i class="fa-solid fa-tags"></i>
                    Organizacja danych
                </span>
                <h1 class="page-header__title">Kategorie</h1>
                <p class="page-header__description">Porządkuj przychody i wydatki, aby raporty były jeszcze czytelniejsze.</p>
            </div>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Dodaj kategorię
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="section-card__header">
                            <div>
                                <h2 class="section-card__title">Twoje kategorie</h2>
                                <p class="section-card__description">Zarządzaj listą kategorii dla bieżących i przyszłych wpisów.</p>
                            </div>
                        </div>

                        @if ($categories->isEmpty())
                            <p class="text-muted mb-0">Brak kategorii. Dodaj pierwszą.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table data-table align-middle">
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
                                                                Usuń
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
    </main>
</body>
</html>









