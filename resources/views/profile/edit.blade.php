<!doctype html>
<html lang="pl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edycja profilu</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light has-fixed-navbar">
    @include('partials.navbar')
    
    @include('partials.alerts')
    <div class="container pt-3 pb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card profile-card">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 text-center mb-1">Edycja profilu</h1>
                        <p class="text-muted text-center mb-4">Zaktualizuj dane konta i walutę domyślną.</p>
                        <form method="POST" action="{{ route('profile.update') }}" data-validate-form novalidate>
                            @csrf
                            @method('PUT')

                            <div class="profile-form-section">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Imię</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                        <input
                                            type="text"
                                            id="name"
                                            name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}"
                                            required
                                            minlength="2"
                                            maxlength="100"
                                        >
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Podaj imię (min. 2 znaki).</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">E-mail</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}"
                                            required
                                            maxlength="255"
                                        >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Podaj poprawny adres e-mail.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="default_currency">Waluta domyślna</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-coins"></i></span>
                                        <select
                                            id="default_currency"
                                            name="default_currency"
                                            class="form-select @error('default_currency') is-invalid @enderror"
                                            required
                                        >
                                            <option value="" disabled @selected(old('default_currency', $user->default_currency) === null)>
                                                Wybierz walutę
                                            </option>
                                            <option value="PLN" @selected(old('default_currency', $user->default_currency) === 'PLN')>PLN</option>
                                            <option value="EUR" @selected(old('default_currency', $user->default_currency) === 'EUR')>EUR</option>
                                            <option value="USD" @selected(old('default_currency', $user->default_currency) === 'USD')>USD</option>
                                            <option value="GBP" @selected(old('default_currency', $user->default_currency) === 'GBP')>GBP</option>
                                        </select>
                                        @error('default_currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Wybierz walutę z listy.</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Zapisz zmiany</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="{{ route('profile.password.edit') }}" class="btn btn-outline-secondary w-100">
                                Zmień hasło
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>











