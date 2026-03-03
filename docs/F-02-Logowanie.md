# F-02 Logowanie

## 1. Cel funkcjonalności
Umożliwienie użytkownikowi zalogowania do systemu i bezpiecznego zarządzania sesją.

## 2. Zakres wdrożenia
- Formularz logowania z Bootstrap 5 i ikonami FontAwesome.
- Walidacja frontend i backend.
- Logowanie przez `Auth::attempt` bez ujawniania, czy email istnieje.
- Regeneracja sesji po zalogowaniu.
- Wylogowanie z unieważnieniem sesji i regeneracją tokenu CSRF.
- Ręczne ładowanie assetów z `manifest.json` (lub dev servera lokalnie).

## 3. Struktura plików
- `frontend/assets/js/app.js`
- `frontend/assets/scss/app.scss`
- `vite.config.js`
- `resources/views/auth/login.blade.php`
- `resources/views/partials/alerts.blade.php`
- `resources/views/partials/frontend-assets.blade.php`
- `app/Http/Requests/LoginRequest.php`
- `app/Http/Controllers/Web/Auth/LoginController.php`
- `routes/web.php`
- `tests/Feature/Auth/LoginTest.php`

## 4. Wymagania środowiskowe
- PHP 8.2+
- Composer 2+
- Node.js 20+
- npm 10+
- Relacyjna baza danych wspierana przez Laravel

## 5. Instrukcja uruchomienia
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

## 6. Opis walidacji
Backend (`LoginRequest`):
- `email`: `required|email`
- `password`: `required|min:8`

Frontend (`frontend/assets/js/app.js`):
- sprawdzanie `required`
- minimalna długość hasła (`>= 8`)
- dynamiczne nadawanie klasy `is-invalid`

## 7. Scenariusz testowy
1. Wejdź na `/login`.
2. Podaj poprawne dane użytkownika.
3. Oczekuj przekierowania na `dashboard` (jeśli route istnieje) lub `/`.
4. Oczekuj komunikatu `Zalogowano pomyślnie.`
5. Spróbuj zalogować błędnym hasłem i nieistniejącym emailem.
6. Oczekuj błędu `Nieprawidłowy email lub hasło.`
7. Po zalogowaniu wykonaj `POST /logout`.
8. Oczekuj przekierowania na `/` i komunikatu `Wylogowano.`

## 8. Definition of Done
- Formularz działa.
- Backend validation działa.
- Inline błędy działają.
- Błędne logowanie pokazuje komunikat.
- Regeneracja sesji działa.
- Logout działa.
- Testy przechodzą.
- Bootstrap + FA działają przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze strukturą Laravel.
