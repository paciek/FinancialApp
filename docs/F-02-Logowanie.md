# F-02 Logowanie

## Cel funkcjonalnosci

Umozliwienie uzytkownikowi zalogowania sie do systemu z walidacja danych i bez ujawniania, czy email istnieje.

## Zakres wdrozenia

- Formularz logowania dostepny pod `/login`.
- Walidacja po stronie frontend (HTML5 + JavaScript) i backend (Laravel `FormRequest`).
- Proba logowania przez `Auth::attempt`.
- Regeneracja sesji po poprawnym logowaniu.
- Przekierowanie po sukcesie na `route('dashboard')` (jesli istnieje) lub `/`.
- Komunikaty flash dla sukcesu i wylogowania.

## Struktura plikow

- `frontend/assets/js/app.js`
- `frontend/assets/scss/app.scss`
- `resources/views/auth/login.blade.php`
- `resources/views/partials/alerts.blade.php`
- `resources/views/partials/frontend-assets.blade.php`
- `app/Http/Requests/LoginRequest.php`
- `app/Http/Controllers/Web/Auth/LoginController.php`
- `routes/web.php`
- `tests/Feature/Auth/LoginTest.php`

## Wymagania srodowiskowe

- PHP i Composer zgodne z wymaganiami projektu.
- Node.js oraz npm.
- Skonfigurowane polaczenie do bazy danych.

## Instrukcja uruchomienia

1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `npm install`
6. `npm run build`
7. `php artisan serve`

## Opis walidacji

### Backend (LoginRequest)

- `email`: `required|email`
- `password`: `required|min:8`

### Frontend

- Atrybuty HTML5: `required`, `type=email`, `minlength`, `maxlength`.
- Klasy walidacyjne Bootstrap (`is-invalid`) ustawiane dynamicznie w JavaScript.

## Scenariusz testowy

1. Uzytkownik przechodzi na `/login`.
2. Podaje poprawny `email` i `password`.
3. System wykonuje logowanie i regeneruje sesje.
4. Uzytkownik jest przekierowany na `route('dashboard')` lub `/`.
5. Po wylogowaniu system uniewaznia sesje i wyswietla komunikat sukcesu.

## Definition of Done

- Formularz logowania dziala.
- Walidacja backend dziala.
- Inline bledy w formularzu dzialaja.
- Bledne logowanie zwraca komunikat ogolny.
- Sesja jest regenerowana po logowaniu.
- Wylogowanie dziala.
- Testy przechodza.
- Bootstrap i FontAwesome sa dostepne przez Vite.
- Dokumentacja istnieje.
- Kod jest zgodny ze struktura Laravel.