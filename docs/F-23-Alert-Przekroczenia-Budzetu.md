# F-23 Alert Przekroczenia Budzetu

## Opis funkcjonalnosci
Na dashboardzie wyswietlany jest alert, gdy wydatki biezacego miesiaca przekraczaja ustawiony limit budzetu. Alert zawiera wartosc limitu oraz sume wydatkow i pojawia sie tylko po spelnieniu warunku przekroczenia.

## Logika sprawdzania budzetu
1. Biezacy miesiac i rok pobierane sa z `now()->month` i `now()->year`.
2. Budzet pobierany jest po `user_id`, `month`, `year`.
3. Suma wydatkow wyliczana jest dla transakcji `expense` w biezacym miesiacu.
4. `budgetExceeded` ustawiane jest na `true`, gdy `spent > budget->limit_amount`.

## Integracja z dashboardem
Logika znajduje sie w `DashboardController@index`, a dane przekazywane sa do `resources/views/dashboard/index.blade.php`.

## UI alertu Bootstrap
W widoku dashboardu renderowany jest `alert alert-danger` z ikona `fa-triangle-exclamation`. Alert pojawia sie u gory dashboardu i nie jest widoczny, gdy budzet nie zostal przekroczony.

## Test cases
Plik: `tests/Feature/Budget/BudgetAlertTest.php`
1. Alert pojawia sie po przekroczeniu budzetu.
2. Alert nie pojawia sie gdy budzet nie jest przekroczony.
3. Alert pokazuje poprawne dane budzetu.
4. Endpoint wymaga autoryzacji.

## Definition of Done
- System sprawdza czy budzet zostal przekroczony.
- Alert pojawia sie na dashboardzie.
- Alert pokazuje limit i wydatki.
- Bootstrap alert dziala poprawnie.
- FontAwesome ikona dziala.
- Dane dotyczace tylko zalogowanego uzytkownika.
- Testy przechodza.
- Dokumentacja powstala w `docs/F-23-Alert-Przekroczenia-Budzetu.md`.
- Kod zgodny ze struktura Laravel.
