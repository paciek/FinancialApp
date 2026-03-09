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
    <div class="container register-layout py-4 py-lg-5">
        <div class="row register-layout__row g-4 g-xl-5 align-items-center">
            <div class="col-12 col-lg-6">
                @include('partials.alerts')

                <div class="card register-card rounded-3 fade-in">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="fs-4 fw-bold mb-4">Zaloguj sie na konto</h1>
                        <p class="text-secondary mb-4">Wprowadz swoje dane, aby uzyskac dostep do panelu.</p>

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
                                <label class="form-label" for="password">Haslo</label>
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
                                        <div class="invalid-feedback">Podaj haslo.</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Zaloguj</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-flex">
                <aside class="register-side-panel fade-in fade-in-delay-1 w-100">
                    <div>
                        <p class="register-side-panel__eyebrow mb-3">WITAJ PONOWNIE</p>
                        <h2 class="fs-2 fw-bold mb-3 text-lg-center">Zarzadzaj swoimi finansami efektywnie</h2>
                        <p class="text-secondary mb-4 text-lg-center">
                            Dolacz do tysiecy ludzi, ktorzy juz korzystaja z naszego rozwiazania.
                        </p>
                    </div>

                    <div class="register-illustration mt-2 mx-lg-auto" aria-hidden="true">
                        <div class="register-illustration__line"></div>
                        <div class="register-illustration__bars">
                            <span class="bar bar-1"></span>
                            <span class="bar bar-2"></span>
                            <span class="bar bar-3"></span>
                            <span class="bar bar-4"></span>
                            <span class="bar bar-5"></span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</body>
</html>
