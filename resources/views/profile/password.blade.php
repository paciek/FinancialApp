<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zmiana hasła</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12">
                @include('partials.alerts')

                <div class="card password-change-card">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 mb-4 text-center">Zmiana hasła</h1>

                        <form method="POST" action="{{ route('profile.password.update') }}" data-validate-form novalidate>
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label" for="current_password">Aktualne hasło</label>
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
                                        <div class="invalid-feedback">Podaj aktualne hasło.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">Nowe hasło</label>
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
                                        <div class="invalid-feedback">Hasło musi mieć min. 8 znaków.</div>
                                    @enderror
                                </div>
                                <div class="password-strength mt-2" aria-hidden="true">
                                    <div class="password-strength__bar" data-password-strength-bar></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="password_confirmation">Potwierdź nowe hasło</label>
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
                                        <div class="invalid-feedback">Hasła muszą być identyczne.</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Zmień hasło</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

