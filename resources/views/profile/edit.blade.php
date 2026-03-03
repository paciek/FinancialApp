<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edycja profilu</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light">
    <div class="profile-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    @include('partials.alerts')
                    <div class="card profile-card">
                        <div class="card-body p-4 p-md-5">
                            <h1 class="h4 mb-4 text-center">Edycja profilu</h1>

                            <form method="POST" action="{{ route('profile.update') }}" data-validate-form novalidate>
                                @csrf
                                @method('PUT')

                                <div class="profile-form-section mb-3">
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
                                            maxlength="100"
                                        >
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Pole imię jest wymagane.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="profile-form-section mb-3">
                                    <label class="form-label" for="email">Email</label>
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
                                            <div class="invalid-feedback">Podaj poprawny adres email.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="profile-form-section mb-4">
                                    <label class="form-label" for="default_currency">Domyślna waluta</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-coins"></i></span>
                                        <select
                                            id="default_currency"
                                            name="default_currency"
                                            class="form-select @error('default_currency') is-invalid @enderror"
                                            required
                                        >
                                            @foreach (['PLN', 'EUR', 'USD', 'GBP'] as $currency)
                                                <option
                                                    value="{{ $currency }}"
                                                    @selected(old('default_currency', $user->default_currency) === $currency)
                                                >
                                                    {{ $currency }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('default_currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Wybierz poprawną walutę.</div>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Zapisz zmiany</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
