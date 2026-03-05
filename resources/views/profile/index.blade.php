<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
@include('partials.app-navbar')
<div class="container py-4">
    @include('partials.alerts')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h1 class="h4 mb-3">
                <i class="fa-solid fa-user"></i>
                Profil
            </h1>
            <dl class="row mb-0">
                <dt class="col-sm-3">Login</dt>
                <dd class="col-sm-9">{{ $user->login }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>
            </dl>
        </div>
    </div>
</div>
</body>
</html>
