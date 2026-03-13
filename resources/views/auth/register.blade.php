<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rejestracja</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                @include('partials.alerts')

                <div class="card register-card">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 mb-4 text-center">Utwórz konto</h1>

                        <form method="POST" action="{{ route('register.store') }}" novalidate data-register-form>
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="login">Login</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input
                                        type="text"
                                        id="login"
                                        name="login"
                                        class="form-control @error('login') is-invalid @enderror"
                                        value="{{ old('login') }}"
                                        required
                                        minlength="3"
                                        maxlength="50"
                                    >
                                    @error('login')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Podaj poprawny login (3-50 znaków).</div>
                                    @enderror
                                </div>
                            </div>

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

                            <div class="mb-3">
                                <label class="form-label" for="email_confirmation">Potwierdzenie email</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input
                                        type="email"
                                        id="email_confirmation"
                                        name="email_confirmation"
                                        class="form-control @error('email_confirmation') is-invalid @enderror"
                                        value="{{ old('email_confirmation') }}"
                                        required
                                        maxlength="255"
                                    >
                                    @error('email_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Adresy email muszą być identyczne.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
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
                                        <div class="invalid-feedback">Hasło musi mieć min. 8 znaków.</div>
                                    @enderror
                                </div>
                                <div class="password-strength mt-2" aria-hidden="true">
                                    <div class="password-strength__bar" data-password-strength-bar></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Potwierdzenie hasła</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
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

                            <div class="mb-4 form-check">
                                <input
                                    type="checkbox"
                                    id="terms"
                                    name="terms"
                                    value="1"
                                    class="form-check-input @error('terms') is-invalid @enderror"
                                    {{ old('terms') ? 'checked' : '' }}
                                    required
                                >
                                <label class="form-check-label" for="terms">Akceptuję regulamin</label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Akceptacja regulaminu jest wymagana.</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Załóż konto</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


