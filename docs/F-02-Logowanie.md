# F-02 Logowanie

## 1. Cel funkcjonalności
Umożliwienie użytkownikowi zalogowania się do systemu z walidacją danych i bez ujawniania, czy email istnieje.

## 2. Zakres wdrożenia
- Formularz logowania dostępny pod `/login`.
- Walidacja po stronie frontend (HTML5 + JavaScript) i backend (Laravel `FormRequest`).
- Logowanie przez `Auth::attempt`.
- Regeneracja sesji po poprawnym logowaniu.
- Przekierowanie po sukcesie na `route('dashboard')` (jeśli istnieje) lub `/`.
- Komunikaty flash dla sukcesu i wylogowania.

## 3. Struktura plików
- `frontend/assets/js/app.js`
- `frontend/assets/scss/app.scss`
- `resources/views/auth/login.blade.php`
- `resources/views/partials/alerts.blade.php`
- `resources/views/partials/frontend-assets.blade.php`
- `app/Http/Requests/LoginRequest.php`
- `app/Http/Controllers/Web/Auth/LoginController.php`
- `routes/web.php`
- `tests/Feature/Auth/LoginTest.php`

## 4. Wymagania środowiskowe
- PHP i Composer zgodne z wymaganiami projektu.
- Node.js oraz npm.
- Skonfigurowane połączenie do bazy danych.

## 5. Instrukcja uruchomienia
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `npm install`
6. `npm run build`
7. `php artisan serve`

## 6. Opis walidacji
### Backend (LoginRequest)
- `email`: `required|email`
- `password`: `required|min:8`

### Frontend
- Atrybuty HTML5: `required`, `type=email`, `minlength`, `maxlength`.
- Klasy walidacyjne Bootstrap (`is-invalid`) ustawiane dynamicznie w JavaScript.

## 7. Scenariusz testowy
1. Użytkownik przechodzi na `/login`.
2. Podaje poprawny `email` i `password`.
3. System wykonuje logowanie i regeneruje sesję.
4. Użytkownik jest przekierowany na `route('dashboard')` lub `/`.
5. Po wylogowaniu system unieważnia sesję i wyświetla komunikat sukcesu.

## 8. Definition of Done
- Formularz logowania działa.
- Walidacja backend działa.
- Inline błędy w formularzu działają.
- Błędne logowanie zwraca komunikat ogólny.
- Sesja jest regenerowana po logowaniu.
- Wylogowanie działa.
- Testy przechodzą.
- Bootstrap i FontAwesome są dostępne przez Vite.
- Dokumentacja istnieje.
- Kod jest zgodny ze strukturą Laravel.