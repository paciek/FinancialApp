# F-04 Zmiana Hasla

## 1. Cel funkcjonalnosci
Umozliwienie zalogowanemu uzytkownikowi bezpiecznej zmiany hasla.

## 2. Zakres wdrozenia
- Formularz zmiany hasla dostepny tylko dla zalogowanych.
- Walidacja frontend i backend.
- Weryfikacja aktualnego hasla (`current_password`).
- Zapis nowego hasla jako hash.
- Regeneracja sesji oraz wylogowanie innych sesji, jesli wspierane.

## 3. Struktura plikow
- `frontend/assets/js/app.js`
- `frontend/assets/scss/app.scss`
- `vite.config.js`
- `app/Http/Requests/UpdatePasswordRequest.php`
- `app/Http/Controllers/Web/Profile/PasswordController.php`
- `resources/views/profile/password.blade.php`
- `routes/web.php`
- `tests/Feature/Profile/UpdatePasswordTest.php`
- `docs/F-04-Zmiana-Hasla.md`

## 4. Wymagania srodowiskowe
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

## 6. Opis walidacji
`UpdatePasswordRequest`:
- `current_password`: `required|current_password`
- `password`: `required|min:8|confirmed|different:current_password`
- `password_confirmation`: `required`

Frontend (`frontend/assets/js/app.js`):
- `required`
- `minlength` dla nowego hasla
- porownanie `password` i `password_confirmation`
- dynamiczne `is-invalid`
- blokada submit przy bledach

## 7. Scenariusz testowy
1. Zaloguj uzytkownika.
2. Wejdz na `/profile/password`.
3. Podaj poprawne aktualne haslo i nowe haslo z potwierdzeniem.
4. Zatwierdz formularz.
5. Oczekuj komunikatu `Haslo zostalo zmienione.` i zapisu nowego hasla.
6. Zweryfikuj przypadki bledne: zle `current_password`, brak potwierdzenia, nowe haslo takie samo jak stare oraz brak dostepu dla goscia.

## 8. Definition of Done
- Formularz dziala.
- Middleware auth dziala.
- Walidacja backend dziala.
- `current_password` dziala poprawnie.
- Haslo zapisywane jako hash.
- Sesja regenerowana.
- Inline bledy UI dzialaja.
- Testy przechodza.
- Bootstrap + FontAwesome dzialaja przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze struktura Laravel.
