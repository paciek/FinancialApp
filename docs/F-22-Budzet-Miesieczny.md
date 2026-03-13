# F-22 Budzet Miesieczny

## Opis funkcjonalnosci
Uzytkownik moze ustawic miesieczny limit wydatkow (tylko transakcje typu `expense`). System wylicza sume wydatkow w biezacym miesiacu, pokazuje pozostaly budzet i wizualizuje wykorzystanie na pasku postepu.

## Struktura tabeli monthly_budgets
Tabela: `monthly_budgets`
- `id`
- `user_id` (relacja do `users.id`)
- `month`
- `year`
- `limit_amount`
- `created_at`
- `updated_at`

## Logika obliczania wydatkow
1. Biezacy miesiac i rok pochodza z `now()->month` oraz `now()->year`.
2. Budzet pobierany jest po `user_id`, `month`, `year`.
3. Suma wydatkow:
`Transaction::where('user_id', auth()->id())->where('type', 'expense')->whereMonth(...)->whereYear(...)->sum('amount')`
4. Obliczane sa:
   - `spent_amount`
   - `remaining_amount`
   - `percentage_used`

## UI progress bar
Wykorzystany jest Bootstrap `progress`. Kolor paska zalezy od procentu wykorzystania:
- 0-60%: `bg-success`
- 60-90%: `bg-warning`
- 90%+: `bg-danger`

## Test cases
Plik: `tests/Feature/Budget/BudgetTest.php`
1. Uzytkownik moze ustawic budzet.
2. Budzet zapisuje sie w bazie.
3. Wydatki sa poprawnie sumowane.
4. Progress bar pokazuje poprawny procent.
5. Endpointy wymagaja autoryzacji.

## Definition of Done
- Uzytkownik moze ustawic limit budzetu.
- System oblicza wydatki z biezacego miesiaca.
- System pokazuje ile budzetu pozostalo.
- Progress bar dziala poprawnie.
- Kolory zmieniaja sie zalezne od wykorzystania.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Testy przechodza.
- Dokumentacja istnieje w `docs/F-22-Budzet-Miesieczny.md`.
- Kod zgodny ze struktura Laravel.
