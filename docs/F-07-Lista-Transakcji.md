# F-07 - Lista Transakcji

## Cel
Pokazanie listy transakcji zalogowanego uzytkownika z paginacja i relacja do kategorii.

## F-09 - Filtrowanie

### Parametry GET
- `date_from` (nullable, date)
- `date_to` (nullable, date, `after_or_equal:date_from`)
- `type` (nullable, `income|expense`)
- `category_id` (nullable, musi nalezec do zalogowanego usera)

### Walidacja
Walidacja jest wykonywana bez osobnego Request, bezposrednio w `TransactionController@index`:
- daty
- typ
- kategoria z `Rule::exists(...)->where('user_id', auth()->id())`

### Przykladowe URL
- `/transactions?type=expense&date_from=2025-01-01`
- `/transactions?date_from=2025-01-01&date_to=2025-12-31`
- `/transactions?type=income&category_id=3`
- `/transactions?type=expense&date_from=2025-01-01&date_to=2025-06-30&category_id=2`

### Test Cases
Plik: `tests/Feature/Transaction/FilterTransactionTest.php`
1. Filtrowanie po typie dziala.
2. Filtrowanie po zakresie dat dziala.
3. Filtrowanie po kategorii dziala.
4. Kombinacja filtrow dziala.
5. Uzytkownik nie moze filtrowac cudzych kategorii.
6. `date_to < date_from` zwraca blad walidacji.

### Definition of Done
- [x] Filtrowanie dziala po zakresie dat
- [x] Filtrowanie dziala po typie
- [x] Filtrowanie dziala po kategorii
- [x] Kombinacja filtrow dziala
- [x] Paginacja zachowuje query string
- [x] Walidacja dziala
- [x] Middleware auth dziala
- [x] Brak dostepu do cudzych danych
- [x] Testy przechodza
- [x] Bootstrap + FontAwesome dzialaja
- [x] Dokumentacja zaktualizowana
- [x] Kod zgodny ze struktura Laravel

