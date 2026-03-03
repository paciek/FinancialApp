# F-05 Tworzenie Kategorii

## 1. Cel funkcjonalności
Umożliwienie zalogowanemu użytkownikowi tworzenia własnych kategorii finansowych typu przychód lub wydatek.

## 2. Zakres wdrożenia
- Model i migracja tabeli `categories`.
- Formularz tworzenia kategorii dostępny tylko dla zalogowanych.
- Lista kategorii użytkownika.
- Walidacja backend i frontend.
- Unikalność nazwy kategorii w obrębie jednego użytkownika.

## 3. Struktura plików
- `database/migrations/2026_03_03_190000_create_categories_table.php`
- `app/Models/Category.php`
- `app/Models/User.php`
- `app/Http/Requests/StoreCategoryRequest.php`
- `app/Http/Controllers/Web/CategoryController.php`
- `resources/views/categories/index.blade.php`
- `resources/views/categories/create.blade.php`
- `frontend/assets/js/app.js`
- `frontend/assets/scss/app.scss`
- `routes/web.php`
- `tests/Feature/Category/CreateCategoryTest.php`

## 4. Struktura tabeli categories
- `id`
- `user_id` (foreign key do `users`, `cascadeOnDelete`)
- `name` (`string(100)`)
- `type` (`enum`: `income`, `expense`)
- `timestamps`
- indeks unikalny: `unique(user_id, name)`

## 5. Wymagania środowiskowe
- PHP 8.2+
- Composer 2+
- Node.js 20+
- npm 10+
- MySQL / MariaDB kompatybilna z Laravel

## 6. Instrukcja uruchomienia
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

## 7. Opis walidacji
Backend (`StoreCategoryRequest`):
- `name`: `required|string|max:100|unique per user`
- `type`: `required|in:income,expense`

Frontend (`frontend/assets/js/app.js`):
- `required`
- `maxlength`
- walidacja radio/select dla pola `type`
- dynamiczna klasa `is-invalid`

## 8. Scenariusz testowy
1. Zaloguj użytkownika.
2. Wejdź na `/categories/create`.
3. Wypełnij formularz poprawnymi danymi i zapisz.
4. Oczekuj przekierowania na `/categories` z komunikatem sukcesu.
5. Powtórz z pustą nazwą, błędnym typem i duplikatem nazwy dla tego samego użytkownika.
6. Zweryfikuj, że inny użytkownik może zapisać kategorię o tej samej nazwie.

## 9. Definition of Done
- Formularz działa.
- Middleware `auth` działa.
- Walidacja backend działa.
- Inline błędy UI działają.
- Kategoria zapisywana z `user_id`.
- Unikalność per użytkownik działa.
- Testy przechodzą.
- Bootstrap + FontAwesome działają przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze strukturą Laravel.
