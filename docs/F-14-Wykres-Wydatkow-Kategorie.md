# F-14 - Wykres Wydatkow wg Kategorii

## 1. Opis funkcjonalnosci
Na stronie podsumowania wyswietlany jest wykres doughnut pokazujacy sume wydatkow dla kazdej kategorii uzytkownika.

## 2. Cel biznesowy
Szybki wglad w strukture wydatkow i identyfikacja kategorii generujacych najwieksze koszty.

## 3. Endpoint
- GET /reports/summary

## 4. Logika agregacji danych
- Pobierane sa transakcje uzytkownika o typie expense.
- Dane sa grupowane po category_id i sumowane.
- Do wykresu przekazywane sa etykiety, wartosci oraz kolory kategorii.

## 5. Kolory kategorii
- Kolor przechowywany jest w kolumnie categories.color (HEX).
- Przyklad: #0d6efd, #198754, #dc3545, #ffc107, #6f42c1.

## 6. Integracja z Chart.js
- Wykres typu doughnut.
- Dane przekazywane przez @json do JS.
- Legendy wyswietlane na dole.

## 7. Struktura danych wykresu
- labels: lista nazw kategorii
- data: suma wydatkow na kategorie
- backgroundColor: kolory kategorii

## 8. Test cases
1. Endpoint summary zwraca dane wykresu.
2. Tylko transakcje typu expense sa liczone.
3. Dane sa pogrupowane po kategorii.
4. Kazda kategoria ma przypisany kolor.
5. Dane dotycza tylko zalogowanego uzytkownika.
6. Endpoint wymaga autoryzacji.

## 9. Definition of Done
- Wykres wydatkow wg kategorii dziala.
- Kazda kategoria ma wlasny kolor.
- Dane sa agregowane poprawnie.
- Tylko transakcje typu expense sa liczone.
- Dane dotycza tylko zalogowanego uzytkownika.
- Wykres dziala na stronie /reports/summary.
- Chart.js poprawnie zaladowany.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Testy przechodza.
- Dokumentacja powstala w docs/F-14-Wykres-Wydatkow-Kategorie.md.
- Kod zgodny ze struktura Laravel.
