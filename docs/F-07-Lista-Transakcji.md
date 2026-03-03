# F-07 - Lista Transakcji

## 1. Cel funkcjonalnosci
Udostepnic zalogowanemu uzytkownikowi liste jego transakcji finansowych w formie tabeli z mozliwoscia filtrowania i paginacji.

## 2. Struktura tabeli transactions
- `id`
- `user_id` (foreign key do `users`, `cascadeOnDelete`)
- `category_id` (foreign key do `categories`, `restrictOnDelete`)
- `amount` (`decimal(12,2)`)
- `type` (`income` lub `expense`)
- `description` (nullable)
- `transaction_date` (`date`)
- `timestamps`

Indeksy:
- `index(user_id)`
- `index(transaction_date)`

## 3. Relacje modelowe
- `Transaction`:
  - `user()` -> `belongsTo(User::class)`
  - `category()` -> `belongsTo(Category::class)`
- `User`:
  - `transactions()` -> `hasMany(Transaction::class)`

## 4. Zakres wdrozenia
- migracja tabeli `transactions`
- model `Transaction`
- relacja `transactions()` w modelu `User`
- kontroler `TransactionController@index`
- widok `transactions.index` z tabela i filtrem
- route resource tylko `index` pod middleware `auth`
- potwierdzenie `confirm()` dla formularzy usuwania (JS)
- testy feature dla listy, filtrowania, dostepu i paginacji

## 5. Wymagania srodowiskowe
- PHP 8.2+
- Composer
- Node.js + npm
- baza danych zgodna z konfiguracja `.env`

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

## 7. Opis filtrowania
Na stronie listy transakcji dostepne sa filtry:
- `type` (`income` / `expense`)
- `date_from`
- `date_to`

Filtrowanie jest wykonywane po stronie backendu i zachowuje parametry podczas paginacji (`withQueryString()`).

## 8. Scenariusz testowy
Plik: `tests/Feature/Transaction/TransactionIndexTest.php`

Scenariusze:
1. Zalogowany uzytkownik widzi swoje transakcje.
2. Uzytkownik nie widzi transakcji innych uzytkownikow.
3. Gosc nie ma dostepu.
4. Filtrowanie po typie dziala.
5. Paginacja dziala (`15` rekordow na stronie).

## 9. Definition of Done
- [x] Lista wyswietla tylko dane uzytkownika
- [x] Paginacja dziala
- [x] Badge typu dziala
- [x] Relacja z kategoria dziala
- [x] Middleware `auth` dziala
- [x] Filtrowanie dziala
- [x] Testy przechodza
- [x] Bootstrap + FontAwesome dzialaja przez Vite
- [x] Dokumentacja istnieje
- [x] Kod zgodny ze struktura Laravel

