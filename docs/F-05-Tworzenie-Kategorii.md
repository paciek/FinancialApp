# F-05 — Tworzenie Kategorii

## 1. Cel funkcjonalnosci
Umozliwic zalogowanemu uzytkownikowi tworzenie wlasnych kategorii finansowych z typem przychod/wydatek.

## 2. Zakres wdrozenia
- Formularz tworzenia kategorii z Bootstrap 5 i FontAwesome.
- Walidacja danych w StoreCategoryRequest.
- Zapis kategorii z przypisaniem do uzytkownika.
- Lista kategorii uzytkownika.
- Testy funkcjonalne scenariuszy.

## 3. Struktura plikow
- app/Http/Controllers/Web/CategoryController.php
- app/Http/Requests/StoreCategoryRequest.php
- app/Models/Category.php
- app/Models/User.php
- database/migrations/2026_03_11_130000_create_categories_table.php
- resources/views/categories/create.blade.php
- resources/views/categories/index.blade.php
- frontend/assets/js/app.js
- frontend/assets/scss/app.scss
- routes/web.php
- tests/Feature/Category/CreateCategoryTest.php
- docs/F-05-Tworzenie-Kategorii.md

## 4. Struktura tabeli categories
- id
- user_id (foreign key -> users, cascadeOnDelete)
- name (string, max 100)
- type (enum: income, expense)
- timestamps
- unique(user_id, name)

## 5. Wymagania srodowiskowe
- PHP 8.2+
- Composer
- Node.js 18+ oraz npm
- Baza danych zgodna z konfiguracja .env

## 6. Instrukcja uruchomienia
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `npm install`
6. `npm run build`
7. `php artisan serve`

## 7. Opis walidacji
- `name`: `required|string|max:100` oraz unikalnosc w obrebie uzytkownika
- `type`: `required|in:income,expense`

## 8. Scenariusz testowy
1. Zalogowany uzytkownik moze utworzyc kategorie.
2. Gosc nie ma dostepu.
3. Nazwa jest wymagana.
4. Typ musi byc income lub expense.
5. Nazwa jest unikalna w obrebie uzytkownika.
6. Dwoch roznych uzytkownikow moze miec te sama nazwe.

## 9. Definition of Done
- Formularz dziala.
- Middleware auth dziala.
- Walidacja backend dziala.
- Inline bledy UI.
- Kategoria zapisywana z user_id.
- Unikalnosc per uzytkownik dziala.
- Testy przechodza.
- Bootstrap + FA dzialaja przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze struktura Laravel.