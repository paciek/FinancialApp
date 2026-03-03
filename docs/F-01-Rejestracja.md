# F-01 Rejestracja

## 1. Cel biznesowy
Umozliwienie gosciowi utworzenia konta w aplikacji i przejscia do ekranu logowania po poprawnej rejestracji.

## 2. Zakres wdrozenia
- Formularz rejestracji w Bootstrap 5 z ikonami FontAwesome.
- Walidacja frontend (HTML5 + JS) oraz backend (FormRequest).
- Zapis nowego uzytkownika do tabeli `users`.
- Przekierowanie po sukcesie na `login` z komunikatem sukcesu.
- Brak automatycznego logowania po rejestracji.

## 3. Struktura plikow
- `frontend/assets/js/app.js`
- `frontend/assets/scss/app.scss`
- `vite.config.js`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/partials/alerts.blade.php`
- `resources/views/partials/frontend-assets.blade.php`
- `app/Http/Requests/RegisterRequest.php`
- `app/Http/Controllers/Web/Auth/RegisterController.php`
- `database/migrations/2026_03_02_170800_add_login_to_users_table.php`
- `routes/web.php`
- `tests/Feature/Auth/RegisterTest.php`

## 4. Wymagania srodowiskowe
- PHP 8.2+
- Composer 2+
- Node.js 20+
- npm 10+
- Relacyjna baza danych zgodna z Laravel

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
Dodano migracje `2026_03_02_170800_add_login_to_users_table.php`, ktora rozszerza tabele `users` o kolumne `login` z unikalnym indeksem.

## 7. Opis walidacji
Backend (`RegisterRequest`):
- `login`: required|string|min:3|max:50|unique:users|alpha_dash
- `email`: required|email|max:255|unique:users
- `email_confirmation`: required|same:email
- `password`: required|min:8|confirmed
- `password_confirmation`: required
- `terms`: required|accepted

Frontend:
- `required`, `type=email`, `minlength`, `maxlength`, `invalid-feedback`.
- JS porownuje `email` z `email_confirmation` i `password` z `password_confirmation`.
- Dynamicznie ustawia `is-invalid` i blokuje submit przy bledach.

## 8. Scenariusz testowy
1. Wejdz na `/register`.
2. Wypelnij formularz poprawnymi danymi i zaakceptuj regulamin.
3. Zatwierdz formularz.
4. Oczekiwany rezultat: przekierowanie na `/login` i komunikat `Konto zostalo utworzone. Zaloguj sie.`.
5. Powtorz z duplikatem loginu/email oraz bez `terms` i zweryfikuj bledy inline.

## 9. Definition of Done
- Formularz dziala.
- Backend validation dziala.
- Inline bledy dzialaja.
- Regulamin wymagany.
- Redirect na login z komunikatem.
- Testy przechodza.
- Bootstrap + FontAwesome dzialaja przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze struktura Laravel.

