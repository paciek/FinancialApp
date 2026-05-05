<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eksport danych</title>
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
                    <i class="fa-solid fa-download"></i>
                    Kopia danych
                </span>
                <h1 class="page-header__title">Eksport danych</h1>
                <p class="page-header__description">Pobierz pliki do analizy, archiwizacji albo importu w innym narzędziu.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card surface-card border-0 h-100">
                    <div class="card-body">
                        <h2 class="h5">Eksport CSV</h2>
                        <p class="text-muted">Pobierz wszystkie transakcje w formacie CSV.</p>
                        <a href="{{ route('export.csv') }}" class="btn btn-primary">
                            <i class="fa-solid fa-file-csv"></i>
                            Pobierz CSV
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card surface-card border-0 h-100">
                    <div class="card-body">
                        <h2 class="h5">Eksport JSON</h2>
                        <p class="text-muted">Pobierz wszystkie transakcje w formacie JSON.</p>
                        <a href="{{ route('export.json') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-file-code"></i>
                            Pobierz JSON
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
</body>
</html>




