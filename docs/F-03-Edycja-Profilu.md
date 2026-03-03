# F-03 Edycja Profilu

## 1. Cel funkcjonalności
Umożliwienie zalogowanemu użytkownikowi edycji danych profilu: imienia, adresu email i domyślnej waluty.

## 2. Zakres wdrożenia
- Formularz edycji profilu dostępny tylko dla zalogowanych.
- Walidacja backend w dedykowanym `FormRequest`.
- Walidacja frontend (required, email format, maxlength, dynamiczne `is-invalid`).
- Zapis zmian użytkownika i komunikat sukcesu.
- Integracja Bootstrap + FontAwesome przez Vite z aktywnych źródeł `frontend/assets/*`.

## 3. Struktura plików
- `frontend/assets/js/app.js`
- `frontend/assets/scss/app.scss`
- `vite.config.js`
- `resources/views/partials/alerts.blade.php`
- `resources/views/partials/frontend-assets.blade.php`
- `resources/views/profile/edit.blade.php`
- `app/Http/Requests/UpdateProfileRequest.php`
- `app/Http/Controllers/Web/ProfileController.php`
- `routes/web.php`
- `database/migrations/2026_03_03_120000_add_default_currency_to_users_table.php`
- `tests/Feature/Profile/UpdateProfileTest.php`

## 4. Wymagania środowiskowe
- PHP 8.2+
- Composer 2+
- Node.js 20+
- npm 10+
- Baza danych wspierana przez Laravel

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

## 6. Informacja o migracji
Dodano migrację `2026_03_03_120000_add_default_currency_to_users_table.php`, która:
- dodaje kolumnę `default_currency` (`string(3)`)
- ustawia wartość domyślną `PLN`

## 7. Opis walidacji
`UpdateProfileRequest`:
- `name`: `required|string|min:2|max:100`
- `email`: `required|email|max:255|unique:users,email,{current_user_id}`
- `default_currency`: `required|in:PLN,EUR,USD,GBP`

## 8. Scenariusz testowy
1. Zaloguj użytkownika.
2. Wejdź na `/profile`.
3. Zmień `name`, `email`, `default_currency`.
4. Zapisz formularz.
5. Oczekuj komunikatu `Profil został zaktualizowany.` i zapisanych danych w bazie.
6. Przetestuj przypadki błędne: duplikat email, waluta spoza listy, brak imienia, dostęp gościa.

## 9. Definition of Done
- Formularz działa.
- Middleware `auth` działa.
- Walidacja backend działa.
- Inline błędy UI działają.
- Aktualizacja zapisuje dane.
- Unikalność email działa poprawnie.
- Waluta zapisywana poprawnie.
- Testy przechodzą.
- Bootstrap + FontAwesome działają przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze strukturą Laravel.
