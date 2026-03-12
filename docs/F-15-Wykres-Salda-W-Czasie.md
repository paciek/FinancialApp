# F-15 - Wykres Salda w Czasie

## 1. Opis funkcjonalnosci
Na stronie podsumowania wyswietlany jest wykres liniowy prezentujacy saldo miesieczne uzytkownika (przychody minus wydatki).

## 2. Cel biznesowy
Szybki wglad w zmiany salda w czasie i obserwacja trendow finansowych.

## 3. Endpoint
- GET /reports/summary

## 4. Logika obliczania salda
- Saldo miesieczne = suma przychodow - suma wydatkow w danym miesiacu.

## 5. Agregacja dzienna
- Dane grupowane po dniu (format YYYY-MM-DD).
- Wykres pokazuje punkty tylko dla dni, w ktorych zaszla zmiana salda.
- Sortowanie rosnaco po dacie.

## 6. Integracja z Chart.js
- Wykres typu line.
- Dane przekazywane przez @json do JS.
- Legenda wyswietlana na dole.

## 7. Struktura danych wykresu
- labels: lista dni (YYYY-MM-DD)
- data: saldo skumulowane w czasie

## 8. Test cases
1. Endpoint summary zwraca dane wykresu.
2. Saldo miesieczne oblicza sie poprawnie.
3. Dane sa pogrupowane po miesiacach.
4. Dane dotycza tylko zalogowanego uzytkownika.
5. Endpoint wymaga autoryzacji.

## 9. Definition of Done
- Wykres salda w czasie dziala.
- Dane sa pogrupowane dziennie.
- Saldo jest poprawnie obliczane.
- Dane dotycza tylko zalogowanego uzytkownika.
- Wykres dziala na stronie /reports/summary.
- Chart.js poprawnie zaladowany.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Testy przechodza.
- Dokumentacja powstala w docs/F-15-Wykres-Salda-W-Czasie.md.
- Kod zgodny ze struktura Laravel.
