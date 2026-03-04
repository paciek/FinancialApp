# F-07 Lista Transakcji

## 1. Cel funkcjonalnosci
Wyswietlanie listy transakcji finansowych zalogowanego uzytkownika z zachowaniem bezpieczenstwa danych per uzytkownik.

## 2. Struktura tabeli transactions
- id
- user_id (foreign key do users, cascadeOnDelete)
- category_id (foreign key do categories, restrictOnDelete)
- amount (decimal 12,2)
- type (enum: income, expense)
- description (nullable string)
- transaction_date (date)
- deleted_at (soft delete)
- timestamps

Dodatkowe indeksy:
- index(user_id)
- index(transaction_date)
- index(description)

## 3. Relacje modelowe
- User hasMany Transaction
- User hasMany Category
- Transaction belongsTo User
- Transaction belongsTo Category
- Category belongsTo User

## 4. Zakres wdrozenia
- Kontroler: `TransactionController@index`
- Widok: `resources/views/transactions/index.blade.php`
- Route: `GET /transactions` (auth)
- Paginacja (15 rekordow)
- Integracja filtrowania i sortowania przez GET params

## 5. Wymagania srodowiskowe
- PHP 8.2+
- Laravel 12.x
- MySQL/MariaDB
- Node.js 20+
- npm 10+

## 6. Instrukcja uruchomienia
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `npm install`
6. `npm run build`
7. `php artisan serve`

## 7. Opis filtrowania
Obslugiwane parametry GET:
- `date_from` (nullable|date)
- `date_to` (nullable|date|after_or_equal:date_from)
- `type` (nullable|in:income,expense)
- `category_id` (nullable, musi nalezec do zalogowanego uzytkownika)

Filtrowanie dziala lacznie i jest zachowywane w paginacji (`withQueryString`).

## 8. F-10 Sortowanie
Parametry GET:
- `sort`: `transaction_date` lub `amount` (domyslnie `transaction_date`)
- `direction`: `asc` lub `desc` (domyslnie `desc`)

Przyklad:
- `/transactions?sort=amount&direction=asc`

Sortowanie dziala razem z filtrami i paginacja.

## 9. F-19 Wyszukiwarka
Wyszukiwanie po opisie transakcji:
- parametr GET: `q`
- walidacja: `nullable|string|max:255`
- zapytanie: `description LIKE %q%`

Przyklad:
- `/transactions?q=zakupy`
- `/transactions?q=zakupy&type=expense&date_from=2026-01-01&sort=amount&direction=asc`

Integracja:
- dziala razem z filtrami (F-09)
- dziala razem z sortowaniem (F-10)
- dziala z paginacja (query string zachowany)
- uwzglednia tylko transakcje zalogowanego usera
- soft deleted sa domyslnie ukryte

## 10. Scenariusz testowy
Plik: `tests/Feature/Transaction/SearchTransactionTest.php`
- wyszukiwanie zwraca poprawne wyniki
- wyszukiwanie jest case insensitive
- nie zwraca transakcji innych uzytkownikow
- nie zwraca soft deleted
- dziala z filtrami
- dziala z sortowaniem
- paginacja zachowuje `q`
- puste `q` nie psuje listy
- brak mozliwosci filtrowania cudza kategoria

## 11. Definition of Done
- Wyszukiwanie dziala po opisie
- Dziala z filtrami
- Dziala z sortowaniem
- Dziala z paginacja
- Soft deleted niewidoczne
- Brak dostepu do cudzych danych
- Indeks `description` dodany
- Testy przechodza
- Bootstrap + FontAwesome dzialaja
- Dokumentacja jest aktualna
- Kod zgodny ze struktura Laravel
