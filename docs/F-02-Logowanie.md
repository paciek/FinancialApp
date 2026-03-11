# F-02 — Logowanie

## 1. Cel funkcjonalnosci
Umozliwic uzytkownikowi bezpieczne zalogowanie sie do systemu z walidacja danych i komunikatami flash.

## 2. Zakres wdrozenia
- Formularz logowania z Bootstrap 5 i FontAwesome.
- Walidacja po stronie backendu (LoginRequest) i prosta walidacja po stronie frontendowej.
- Logika logowania, regeneracja sesji i wylogowanie.
- Testy funkcjonalne scenariuszy logowania i wylogowania.

## 3. Struktura plikow
- app/Http/Controllers/Web/Auth/LoginController.php
- app/Http/Requests/LoginRequest.php
- resources/views/auth/login.blade.php
- resources/views/partials/alerts.blade.php
- frontend/assets/js/app.js
- frontend/assets/scss/app.scss
- routes/web.php
- tests/Feature/Auth/LoginTest.php
- docs/F-02-Logowanie.md

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

## 6. Opis walidacji
- `email`: `required|email`
- `password`: `required|min:8`
- Przy blednych danych zwracany jest blad: "Nieprawidlowy email lub haslo."

## 7. Scenariusz testowy
1. Uzytkownik loguje sie poprawnymi danymi i jest przekierowany na dashboard lub `/`.
2. Uzytkownik loguje sie blednym haslem i otrzymuje blad walidacji.
3. Uzytkownik loguje sie nieistniejacym emailem i otrzymuje ten sam blad.
4. Puste pola `email` i `password` zwracaja bledy walidacji.
5. Wylogowanie uniewaznia sesje i zwraca komunikat sukcesu.

## 8. Definition of Done
- Formularz dziala.
- Backend validation dziala.
- Inline bledy dzialaja.
- Bledne logowanie pokazuje komunikat.
- Regeneracja sesji dziala.
- Logout dziala.
- Testy przechodza.
- Bootstrap + FontAwesome dzialaja przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze struktura Laravel.
