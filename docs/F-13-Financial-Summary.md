# F-13 Financial Summary

## Cel funkcjonalności
Wyświetlenie podsumowania finansowego zalogowanego użytkownika:
- suma przychodów
- suma wydatków
- saldo (`income - expense`)

Podsumowanie działa na tej samej bazie filtrów co lista transakcji.

## Mechanizm agregacji
W `TransactionController@index` po zbudowaniu bazowego zapytania z:
- filtrowaniem (`date_from`, `date_to`, `type`, `category_id`)
- wyszukiwaniem (`q`)
- ograniczeniem do użytkownika (`where user_id = auth()->id()`)

tworzona jest kopia zapytania:

```php
$summaryQuery = clone $query;
```

Następnie wykonywane jest jedno zapytanie SQL agregujące:

```php
$summary = $summaryQuery
    ->selectRaw("
        SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as total_income,
        SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as total_expense
    ")
    ->first();
```

Wyliczenia:
- `$totalIncome = $summary->total_income ?? 0`
- `$totalExpense = $summary->total_expense ?? 0`
- `$balance = $totalIncome - $totalExpense`

## Integracja z filtrami
Summary reaguje na:
- zakres dat
- typ transakcji
- kategorię
- wyszukiwarkę po opisie (`q`)

Ponieważ bazuje na klonie już przefiltrowanego zapytania, zawsze odpowiada dokładnie aktualnej liście.

## Bezpieczeństwo
- `middleware auth`
- `where('user_id', auth()->id())`
- walidacja `category_id` z ograniczeniem do kategorii użytkownika
- soft deleted ignorowane (brak `withTrashed()`)

## Test cases
Plik: `tests/Feature/Transaction/FinancialSummaryTest.php`

Scenariusze:
1. poprawnie liczy przychody
2. poprawnie liczy wydatki
3. poprawnie liczy saldo
4. nie uwzględnia soft deleted
5. nie uwzględnia cudzych danych
6. reaguje na filtr dat
7. reaguje na wyszukiwanie

## Definition of Done
- jedno zapytanie SQL dla podsumowania
- poprawne sumy i saldo
- podsumowanie reaguje na filtry/szukaj/sort
- ignoruje soft deleted
- brak dostępu do cudzych danych
- testy przechodzą
- UI oparte o Bootstrap + FontAwesome
- kod zgodny ze strukturą Laravel

