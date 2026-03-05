# F-14 Expense By Category Report

## Endpoint
- `GET /reports/expenses-by-category`
- route name: `reports.expenses.byCategory`
- middleware: `auth`

## Parametry GET
- `date_from` (nullable, date)
- `date_to` (nullable, date, `after_or_equal:date_from`)

Przykład:
- `/reports/expenses-by-category?date_from=2026-01-01&date_to=2026-01-31`

## Agregacja SQL
Raport buduje zapytanie dla zalogowanego użytkownika, tylko typu `expense`, a następnie agreguje:

```sql
select category_id, sum(amount) as total
from transactions
where user_id = ?
  and type = 'expense'
group by category_id
```

Soft deleted są ignorowane domyślnie przez Eloquent (`SoftDeletes`) bez `withTrashed()`.

## Chart.js
Frontend używa `chart.js/auto` i renderuje `doughnut` na podstawie danych z atrybutów:
- `data-labels`
- `data-values`

Konfiguracja:
- `responsive: true`
- legenda na dole (`legend.position = 'bottom'`)

## Bezpieczeństwo
- tylko zalogowany użytkownik (`auth`)
- `where('user_id', auth()->id())`
- brak dostępu do cudzych danych
- brak uwzględniania soft deleted

## Test Cases
Plik: `tests/Feature/Reports/ExpenseByCategoryReportTest.php`

Scenariusze:
1. zalogowany użytkownik widzi raport
2. niezalogowany ma redirect na login
3. dane są poprawnie agregowane
4. soft deleted nie są uwzględnione
5. zakres dat działa poprawnie

## Definition of Done
- nowa podstrona działa
- doughnut chart działa
- filtr dat działa
- soft deleted ignorowane
- tylko dane zalogowanego użytkownika
- testy przechodzą
- Bootstrap + FontAwesome + Chart.js działają
- dokumentacja istnieje
- kod zgodny ze strukturą Laravel

