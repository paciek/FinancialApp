# F-10 - Sortowanie Transakcji

## 1. Cel funkcjonalnosci
Dodanie sortowania listy transakcji po dacie oraz kwocie, z mozliwoscia wyboru kierunku ASC/DESC. Sortowanie dziala razem z filtrami i paginacja, z parametrami w query string.

## 2. Parametry GET
- sort: transaction_date | amount
- direction: asc | desc

## 3. Wartosci domyslne
- sort: transaction_date
- direction: desc

## 4. Przykladowe URL
- /transactions?sort=amount&direction=asc

## 5. Integracja z filtrami
Sortowanie nie resetuje filtrow (parametry query sa laczone) i dziala z paginacja przez withQueryString.

## 6. Test cases
1. Domyslne sortowanie jest po dacie DESC.
2. Sortowanie po dacie ASC dziala.
3. Sortowanie po kwocie ASC dziala.
4. Sortowanie po kwocie DESC dziala.
5. Niedozwolona kolumna zwraca blad walidacji.
6. Sortowanie dziala razem z filtrowaniem.
7. Paginacja zachowuje sort.

## 7. Definition of Done
- Sortowanie dziala po dacie ASC/DESC.
- Sortowanie dziala po kwocie ASC/DESC.
- Domyslnie data DESC.
- Sortowanie dziala z filtrami.
- Sortowanie dziala z paginacja.
- Ikony FontAwesome dzialaja poprawnie.
- Bootstrap styling poprawny.
- Walidacja zabezpiecza kolumny.
- Testy przechodza.
- Dokumentacja zaktualizowana.
- Kod zgodny ze struktura Laravel.
