# F-08 - Dodawanie Transakcji

## 1. Cel funkcjonalnosci
Umozliwienie zalogowanemu uzytkownikowi dodania nowej transakcji finansowej.

## 2. Struktura tabeli transactions
- `id`
- `user_id`
- `category_id`
- `amount` (`decimal(12,2)`)
- `type` (`income|expense`)
- `description` (nullable, max 255)
- `transaction_date` (`date`)
- `timestamps`

## 3. Relacje modelowe
- `Transaction`:
  - `user()` -> `belongsTo(User::class)`
  - `category()` -> `belongsTo(Category::class)`
- `User`:
  - `transactions()` -> `hasMany(Transaction::class)`
  - `categories()` -> `hasMany(Category::class)`

## 4. Zakres wdrozenia
- request walidacyjny `StoreTransactionRequest`
- metody `create()` i `store()` w `TransactionController`
- widok `transactions/create.blade.php`
- aktualizacja route resource do `index/create/store`
- frontend JS:
  - walidacja required i blokada submit przy bledach
  - filtrowanie kategorii po typie transakcji
  - kolorowanie pola kwoty zalezne od typu
- relacje modeli `Category` i `Transaction`

## 5. Wymagania srodowiskowe
- PHP 8.2+
- Composer
- Node.js + npm
- MySQL lub SQLite

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
`StoreTransactionRequest`:
- `transaction_date`: `required|date`
- `amount`: `required|numeric|min:0.01`
- `type`: `required|in:income,expense`
- `category_id`: `required` + `exists` z warunkiem `user_id = auth()->id()`
- `description`: `nullable|string|max:255`

Dodatkowe zabezpieczenie:
- `user_id` transakcji jest ustawiany tylko na backendzie (`auth()->id()`).

## 8. Scenariusz testowy
Plik: `tests/Feature/Transaction/CreateTransactionTest.php`

Scenariusze:
1. Zalogowany uzytkownik moze dodac transakcje.
2. Gosc nie ma dostepu.
3. `category_id` musi nalezec do zalogowanego uzytkownika.
4. `amount` musi byc > 0.
5. `type` musi byc `income` lub `expense`.
6. `description` jest opcjonalny.

## 9. Definition of Done
- [x] Formularz dziala
- [x] Walidacja backend dziala
- [x] Inline bledy UI
- [x] category nalezy do usera
- [x] Transakcja zapisywana z user_id
- [x] Redirect dziala
- [x] Testy przechodza
- [x] Bootstrap + FA dzialaja
- [x] Dokumentacja istnieje
- [x] Kod zgodny ze struktura Laravel

