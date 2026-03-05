<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zmiana hasla</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    @include('partials.app-navbar')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12">
                @include('partials.alerts')

                <div class="card password-change-card">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 mb-4 text-center">Zmiana hasla</h1>

                        <form method="POST" action="{{ route('profile.password.update') }}" data-validate-form novalidate>
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label" for="current_password">Aktualne haslo</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input
                                        type="password"
                                        id="current_password"
                                        name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        required
                                        minlength="8"
                                        maxlength="255"
                                    >
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Podaj aktualne haslo.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">Nowe haslo</label>
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
                                        <div class="invalid-feedback">Haslo musi miec min. 8 znakow.</div>
                                    @enderror
                                </div>
                                <div class="password-strength mt-2" aria-hidden="true">
                                    <div class="password-strength__bar" data-password-strength-bar></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="password_confirmation">Potwierdz nowe haslo</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        required
                                        minlength="8"
                                        maxlength="255"
                                    >
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Hasla musza byc identyczne.</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Zmien haslo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
