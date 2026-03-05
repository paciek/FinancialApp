<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logowanie</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    @include('partials.app-navbar')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                @include('partials.alerts')
                <div class="card register-card">
                    <div class="card-body p-4">
                        <h1 class="h4 text-center mb-4">Logowanie</h1>
                        <form method="POST" action="{{ route('login') }}" data-validate-form novalidate>
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        required
                                        maxlength="255"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Podaj poprawny adres email.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="password">Hasło</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        required
                                        minlength="8"
                                        maxlength="255"
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Podaj hasło.</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Zaloguj</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
