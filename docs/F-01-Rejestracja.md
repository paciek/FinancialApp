# F-01 Rejestracja

## Cel

Umozliwienie gosciowi utworzenia konta w aplikacji i przejscia do ekranu logowania po poprawnej rejestracji.

## Zakres

- Formularz rejestracji dostepny pod `/register`.
- Walidacja po stronie frontend (HTML5 + JavaScript) i backend (Laravel `FormRequest`).
- Zapis nowego uzytkownika do tabeli `users`.
- Przekierowanie po sukcesie na `/login` z komunikatem sukcesu.
- Brak automatycznego logowania po rejestracji.

## Warunki wstepne

- Aplikacja uruchomiona (np. przez Docker Compose).
- Wykonane migracje bazy danych.
- Dostepna tabela `users` z kolumna `login` (migracja `2026_03_02_170800_add_login_to_users_table.php`).
- Assety frontend zbudowane (`npm run build`) lub uruchomiony dev server Vite.

## Struktura techniczna

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

## Kroki (scenariusz glowny)

1. Uzytkownik wchodzi na `/register`.
2. Uzytkownik uzupelnia formularz poprawnymi danymi:
   - `login`
   - `email`
   - `email_confirmation`
   - `password`
   - `password_confirmation`
   - `terms` (akceptacja regulaminu)
3. Uzytkownik wysyla formularz.
4. Backend waliduje dane (`RegisterRequest`).
5. System tworzy nowy rekord w `users` (`RegisterController@register`).
6. System przekierowuje na `/login` i ustawia komunikat sukcesu.

## Walidacje

### Backend (RegisterRequest)

- `login`: `required|string|min:3|max:50|unique:users,login|alpha_dash`
- `email`: `required|email|max:255|unique:users,email`
- `email_confirmation`: `required|same:email`
- `password`: `required|min:8|confirmed`
- `password_confirmation`: `required`
- `terms`: `required|accepted`

### Frontend

- Atrybuty HTML5: `required`, `type=email`, `minlength`, `maxlength`.
- Komunikaty `invalid-feedback` w formularzu.
- JavaScript porownuje:
  - `email` z `email_confirmation`
  - `password` z `password_confirmation`
- Przy bledach formularz nie jest wysylany i pola dostaja klasy walidacyjne.

## Przypadki negatywne

- Duplikat `login` -> blad walidacji pola `login`.
- Duplikat `email` -> blad walidacji pola `email`.
- Brak akceptacji `terms` -> blad walidacji pola `terms`.
- Rozne hasla (`password` vs `password_confirmation`) -> blad pola `password`.
- Rozne adresy email (`email` vs `email_confirmation`) -> blad pola `email_confirmation`.
- Login z niedozwolonymi znakami (np. spacja, `!`) -> blad reguly `alpha_dash`.

## Rezultat

### Sukces

- Rekord uzytkownika zapisany w bazie.
- Przekierowanie na `/login`.
- Komunikat: `Konto zostalo utworzone. Zaloguj sie.` (tresc moze zalezec od kodowania/pliku jezykowego).

### Niepowodzenie

- Brak zapisu do bazy.
- Powrot do formularza rejestracji.
- Wyswietlenie bledow walidacji przy odpowiednich polach.

## Kryteria akceptacji

- Formularz rejestracji jest dostepny i wyswietla wszystkie wymagane pola.
- Walidacja backend i frontend dziala zgodnie z regalami.
- Pole `terms` jest wymagane.
- Po poprawnej rejestracji nastepuje redirect na `/login`.
- Testy funkcjonalne dla rejestracji przechodza (`tests/Feature/Auth/RegisterTest.php`).

## Do uzupelnienia

- Finalna tresc komunikatow UI z polskimi znakami (w repo wystepuja pliki z mieszanym kodowaniem).
- Jednoznaczna polityka kodowania plikow tekstowych (zalecane UTF-8 bez BOM).

