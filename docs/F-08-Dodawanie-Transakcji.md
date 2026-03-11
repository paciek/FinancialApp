# F-08 — Dodawanie Transakcji

## 1. Cel funkcjonalnosci
Umozliwic zalogowanemu uzytkownikowi dodanie nowej transakcji finansowej.

## 2. Struktura tabeli transactions
- id
- user_id (foreign key -> users, cascadeOnDelete)
- category_id (foreign key -> categories, restrictOnDelete)
- amount (decimal 12,2)
- type (enum: income, expense)
- description (string, nullable)
- transaction_date (date)
- timestamps

## 3. Relacje modelowe
- Transaction -> user(): belongsTo(User::class)
- Transaction -> category(): belongsTo(Category::class)
- User -> transactions(): hasMany(Transaction::class)

## 4. Zakres wdrozenia
- Formularz dodawania transakcji.
- Walidacja w StoreTransactionRequest.
- Zapis transakcji z user_id ustawianym na backendzie.

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
- `transaction_date`: `required|date`
- `amount`: `required|numeric|min:0.01`
- `type`: `required|in:income,expense`
- `category_id`: `required|exists:categories,id` z ograniczeniem do usera
- `description`: `nullable|string|max:255`

## 8. Scenariusz testowy
1. Zalogowany uzytkownik moze dodac transakcje.
2. Gosc nie ma dostepu.
3. category_id musi nalezec do uzytkownika.
4. amount > 0.
5. type musi byc income lub expense.
6. description optional dziala.

## 9. Definition of Done
- Formularz dziala.
- Walidacja backend dziala.
- Inline bledy UI.
- Kategoria nalezy do usera.
- Transakcja zapisywana z user_id.
- Redirect dziala.
- Testy przechodza.
- Bootstrap + FA dzialaja.
- Dokumentacja istnieje.
- Kod zgodny ze struktura Laravel.