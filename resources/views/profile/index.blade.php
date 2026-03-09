<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil</title>
    @include('partials.frontend-assets')
</head>
<body class="bg-light with-right-tabs">
    @include('partials.app-navbar')
    <div class="container py-4">
        @include('partials.alerts')

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">
                    <i class="fa-solid fa-user"></i>
                    Profil
                </h1>

                <form method="POST" action="{{ route('profile.update') }}" class="row g-3" data-validate-form novalidate>
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label for="login" class="form-label">Login</label>
                        <input id="login" type="text" class="form-control" value="{{ $user->login }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Nazwa</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                            minlength="2"
                            maxlength="255"
                            pattern="^[A-Za-zÀ-ž\s\-.]+$"
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Nazwa ma nieprawidlowy format.</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            type="email"
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

                    <div class="col-md-6">
                        <label for="contact_phone" class="form-label">Telefon kontaktowy</label>
                        <input
                            id="contact_phone"
                            type="text"
                            name="contact_phone"
                            class="form-control @error('contact_phone') is-invalid @enderror"
                            value="{{ old('contact_phone', $user->contact_phone) }}"
                            maxlength="50"
                            pattern="^\+?[0-9\s\-\(\)]{7,20}$"
                            placeholder="+48 123 456 789"
                        >
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Telefon kontaktowy ma nieprawidlowy format.</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Adres</label>
                        <input
                            id="address"
                            type="text"
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', $user->address) }}"
                            maxlength="255"
                            pattern="^[A-Za-zÀ-ž0-9\s,\.\-\/]+$"
                            placeholder="Ulica, kod pocztowy, miasto"
                        >
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Adres ma nieprawidlowy format.</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
