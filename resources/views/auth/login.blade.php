<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logowanie</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="auth-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    @include('partials.alerts')

                    <div class="card auth-card mx-auto">
                        <div class="card-body p-4 p-md-5">
                            <h1 class="h4 text-center mb-4">Zaloguj się</h1>

                            <form method="POST" action="{{ route('login') }}" data-validate-form novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            required
                                        >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Podaj poprawny email.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Hasło</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                        <input
                                            type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password"
                                            name="password"
                                            required
                                            minlength="8"
                                        >
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Hasło musi mieć co najmniej 8 znaków.</div>
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
    </div>
</body>
</html>
