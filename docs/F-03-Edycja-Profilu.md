# F-03 — Edycja Profilu

## 1. Cel funkcjonalnosci
Umozliwic zalogowanemu uzytkownikowi edycje danych profilu: imie, email oraz domyslna waluta.

## 2. Zakres wdrozenia
- Formularz edycji profilu z Bootstrap 5 i FontAwesome.
- Walidacja danych wejsciowych w UpdateProfileRequest.
- Aktualizacja danych uzytkownika i komunikat sukcesu.
- Ochrona dostepu przez middleware auth.
- Testy funkcjonalne scenariuszy.

## 3. Struktura plikow
- app/Http/Controllers/Web/ProfileController.php
- app/Http/Requests/UpdateProfileRequest.php
- app/Models/User.php
- database/migrations/2026_03_11_114402_add_default_currency_to_users_table.php
- resources/views/profile/edit.blade.php
- frontend/assets/js/app.js
- frontend/assets/scss/app.scss
- routes/web.php
- tests/Feature/Profile/UpdateProfileTest.php
- docs/F-03-Edycja-Profilu.md

## 4. Wymagania srodowiskowe
- PHP 8.2+
- Composer
- Node.js 18+ oraz npm
- Baza danych zgodna z konfiguracja .env

## 5. Instrukcja uruchomienia
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `npm install`
6. `npm run build`
7. `php artisan serve`

## 6. Informacja o migracji
Dodano migracje rozszerzajaca tabele `users` o kolumne `default_currency` typu string(3) z domyslna wartoscia `PLN`.

## 7. Opis walidacji
- `name`: `required|string|min:2|max:100`
- `email`: `required|email|max:255|unique:users,email,{id}`
- `default_currency`: `required|in:PLN,EUR,USD,GBP`

## 8. Scenariusz testowy
1. Zalogowany uzytkownik aktualizuje profil poprawnymi danymi.
2. Gosc nie ma dostepu do edycji profilu.
3. Email musi byc unikalny.
4. Waluta musi byc z dozwolonej listy.
5. Pole `name` jest wymagane.

## 9. Definition of Done
- Formularz dziala.
- Middleware auth dziala.
- Walidacja backend dziala.
- Inline bledy UI.
- Aktualizacja zapisuje dane.
- Email unique dziala poprawnie.
- Waluta zapisywana poprawnie.
- Testy przechodza.
- Bootstrap + FA dzialaja przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze struktura Laravel.