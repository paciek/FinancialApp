# F-07 — Lista Transakcji

## 1. Cel funkcjonalnosci
Wyswietlenie tabeli wszystkich operacji finansowych zalogowanego uzytkownika z paginacja i filtrowaniem.

## 2. Struktura tabeli transactions
- id
- user_id (foreign key -> users, cascadeOnDelete)
- category_id (foreign key -> categories, restrictOnDelete)
- amount (decimal 12,2)
- type (enum: income, expense)
- description (string, nullable)
- transaction_date (date)
- timestamps
- index(user_id)
- index(transaction_date)

## 3. Relacje modelowe
- Transaction -> user(): belongsTo(User::class)
- Transaction -> category(): belongsTo(Category::class)
- User -> transactions(): hasMany(Transaction::class)

## 4. Zakres wdrozenia
- Kontroler z lista transakcji i filtrowaniem.
- Widok tabeli z paginacja i badge typu.
- Migracja tabeli transactions.
- Testy funkcjonalne listy i filtrowania.

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

## 7. Opis filtrowania
- `type`: income/expense
- `date_from`: od daty (transaction_date)
- `date_to`: do daty (transaction_date)

## 8. Scenariusz testowy
1. Zalogowany uzytkownik widzi swoje transakcje.
2. Nie widzi transakcji innych uzytkownikow.
3. Gosc nie ma dostepu.
4. Filtrowanie po typie dziala.
5. Paginacja dziala.

## 9. Definition of Done
- Lista wyswietla tylko dane uzytkownika.
- Paginacja dziala.
- Badge typu dziala.
- Relacja z kategoria dziala.
- Middleware auth dziala.
- Filtrowanie dziala.
- Testy przechodza.
- Bootstrap + FA dzialaja przez Vite.
- Dokumentacja istnieje.
- Kod zgodny ze struktura Laravel.

## 10. F-11 — Edycja transakcji

### Endpointy
- GET /transactions/{transaction}/edit
- PUT /transactions/{transaction}

### Walidacja
- transaction_date: required | date
- amount: required | numeric | min:0
- type: required | in:income,expense
- category_id: required | exists:categories,id (user_id = auth)
- description: nullable | string | max:500

### Formularz
- data, kwota, typ, kategoria, opis
- pola wypelnione danymi transakcji
- bledy walidacji inline

### Przykladowy flow edycji
1. Uzytkownik otwiera edycje transakcji.
2. Wprowadza zmiany w formularzu.
3. Zapisuje i wraca na liste transakcji z komunikatem sukcesu.

### Test cases
1. Uzytkownik moze otworzyc formularz edycji.
2. Dane transakcji sa poprawnie wypelnione.
3. Zapis zmian dziala.
4. Uzytkownik nie moze edytowac cudzej transakcji.
5. Walidacja dziala poprawnie.

### Definition of Done
- Formularz edycji dziala.
- Dane sa wypelnione w formularzu.
- Zmiany zapisuja sie w bazie.
- Uzytkownik moze edytowac tylko swoje transakcje.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Walidacja dziala poprawnie.
- Testy przechodza.
- Dokumentacja zaktualizowana.
- Kod zgodny ze struktura Laravel.
