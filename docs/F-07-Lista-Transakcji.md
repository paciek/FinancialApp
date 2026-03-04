# F-07 - Lista Transakcji

## Cel
Wyswietlanie listy transakcji zalogowanego uzytkownika.

## F-09 - Filtrowanie

### Parametry GET
- `date_from`
- `date_to`
- `type`
- `category_id`

### Walidacja
- `date_from`: `nullable|date`
- `date_to`: `nullable|date|after_or_equal:date_from`
- `type`: `nullable|in:income,expense`
- `category_id`: `Rule::exists('categories', 'id')->where('user_id', auth()->id())`

### Przykladowy URL
- `/transactions?type=expense&date_from=2025-01-01`

## F-10 - Sortowanie

### Parametry GET
- `sort`: `transaction_date|amount`
- `direction`: `asc|desc`

### Domyslne wartosci
- `sort=transaction_date`
- `direction=desc`

### Przykladowe URL
- `/transactions?sort=amount&direction=asc`
- `/transactions?type=expense&sort=transaction_date&direction=desc`

### Integracja z filtrami
Sortowanie i filtrowanie dzialaja razem na jednym zapytaniu Eloquent. Paginacja korzysta z `withQueryString()`, wiec parametry `sort`, `direction` oraz filtry pozostaja przy przechodzeniu miedzy stronami.

### Test Cases
Plik: `tests/Feature/Transaction/SortTransactionTest.php`
1. Domyslne sortowanie po dacie DESC.
2. Sortowanie po dacie ASC.
3. Sortowanie po kwocie ASC.
4. Sortowanie po kwocie DESC.
5. Niedozwolona kolumna sortowania zwraca blad walidacji.
6. Sortowanie dziala razem z filtrowaniem.
7. Paginacja zachowuje parametry sortowania.

### Definition of Done
- [x] Sortowanie po dacie ASC/DESC
- [x] Sortowanie po kwocie ASC/DESC
- [x] Domyslnie data DESC
- [x] Sortowanie wspolpracuje z filtrami
- [x] Sortowanie dziala z paginacja
- [x] Walidacja zabezpiecza kolumny
- [x] Testy przechodza

## F-11 - Usuwanie (Soft Delete)

### Mechanizm soft delete
- tabela `transactions` zawiera kolumne `deleted_at`
- model `Transaction` uzywa traitu `SoftDeletes`
- usuwanie realizowane przez `$transaction->delete()` zamiast hard delete

### Modal potwierdzenia
Na liscie transakcji przycisk usuwania otwiera modal Bootstrap z ostrzezeniem i formularzem `DELETE`:
- przycisk `Anuluj`
- przycisk `Usun` wykonujacy `DELETE /transactions/{transaction}`

### Bezpieczenstwo
- trasa `transactions.destroy` jest w middleware `auth`
- kontroler sprawdza wlasciciela:
  - `abort_if($transaction->user_id !== auth()->id(), 403);`
- soft deleted transakcje sa domyslnie ukryte na liscie (brak `withTrashed()`)

### Test Cases
Plik: `tests/Feature/Transaction/DeleteTransactionTest.php`
1. Uzytkownik moze usunac swoja transakcje.
2. Rekord jest soft deleted (`assertSoftDeleted`).
3. Usunieta transakcja nie widnieje na liscie.
4. Uzytkownik nie moze usunac cudzej transakcji (`403`).
5. Gosc nie moze usuwac (redirect do login).

### Definition of Done
- [x] Soft delete dziala
- [x] `deleted_at` w bazie
- [x] Modal potwierdzenia dziala
- [x] Usunieta transakcja znika z listy
- [x] Brak hard delete
- [x] Brak dostepu do cudzych danych
- [x] Testy przechodza
