<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Export danych</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    @include('partials.navbar')
    @include('partials.alerts')
    <div class="container py-5">
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="h4">
                    <i class="fa-solid fa-download"></i>
                    Export danych
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mt-3">
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
                <div class="card shadow-sm mt-3">
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
</body>
</html>
