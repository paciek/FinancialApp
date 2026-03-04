# F-07 - Lista Transakcji

## Cel
Wyswietlanie, filtrowanie i sortowanie transakcji zalogowanego uzytkownika.

## F-12 - Edycja transakcji

### Flow edycji
1. Uzytkownik klika przycisk edycji w tabeli transakcji.
2. System otwiera formularz `transactions.edit`.
3. Po zapisaniu zmian (`PUT /transactions/{transaction}`) rekord jest aktualizowany.
4. Uzytkownik wraca na liste z komunikatem sukcesu.

### Walidacja
W `TransactionController@update`:
- `transaction_date`: `required|date`
- `amount`: `required|numeric|min:0.01`
- `type`: `required|in:income,expense`
- `category_id`: `required` + `exists` z warunkiem:
  - kategoria nalezy do zalogowanego usera
  - typ kategorii pasuje do wybranego typu transakcji
- `description`: `nullable|string|max:1000`

### Dynamiczne kategorie
W widoku edycji zmiana typu (`income/expense`) uruchamia fetch:
- `/api/categories?type=income` albo `/api/categories?type=expense`

Endpoint zwraca JSON:
- `id`
- `name`

### Zabezpieczenia
- middleware `auth`
- edycja tylko dla wlasciciela transakcji (`403` dla obcych)
- soft deleted nieedytowalne (`404`)
- kategoria walidowana po `user_id` i `type`

### Test cases
Plik: `tests/Feature/Transaction/EditTransactionTest.php`
1. Uzytkownik moze edytowac swoja transakcje.
2. Dane aktualizuja sie w bazie.
3. Nie mozna zmienic na cudza kategorie.
4. Nie mozna edytowac cudzej transakcji (`403`).
5. Nie mozna edytowac soft deleted (`404`).
6. Walidacja dziala.
7. Kwota nie moze byc ujemna.

### Definition of Done
- [x] Edycja dziala
- [x] Walidacja dziala
- [x] Dynamiczne kategorie dzialaja
- [x] Brak dostepu do cudzych danych
- [x] Soft deleted nieedytowalne
- [x] Testy przechodza
- [x] Bootstrap + FontAwesome dzialaja
- [x] Dokumentacja zaktualizowana
- [x] Kod zgodny ze struktura Laravel

