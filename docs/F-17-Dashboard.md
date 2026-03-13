# F-17 - Dashboard Glowny

## 1. Opis funkcjonalnosci
Dashboard glowny prezentuje kluczowe informacje finansowe uzytkownika: podsumowanie, ostatnie transakcje oraz wykresy wydatkow i salda w czasie.

## 2. Cel biznesowy
Zapewnienie szybkiego wgladu w sytuacje finansowa i trend wydatkow bez koniecznosci przegladania listy transakcji.

## 3. Endpoint
- GET /dashboard

## 4. Struktura dashboardu
- Sekcja podsumowania: 3 karty (Przychody, Wydatki, Saldo).
- Sekcja ostatnich transakcji: tabela z 5 rekordami oraz link "Zobacz wszystkie".
- Sekcja wykresow: wykres wydatkow wg kategorii oraz wykres salda w czasie.

## 5. Statystyki
- Liczba transakcji oraz podzial na przychody i wydatki.
- Srednie i najwyzsze wartosci przychodow oraz wydatkow.
- Najwyzsza kategoria wydatkow na podstawie sumy kwot.

## 6. Test cases
1. Uzytkownik widzi dashboard.
2. Podsumowanie finansowe jest poprawne.
3. Widoczne sa ostatnie transakcje.
4. Dane dotycza tylko zalogowanego uzytkownika.
5. Endpoint wymaga autoryzacji.

## 7. Definition of Done
- Dashboard dziala.
- Pokazuje podsumowanie finansowe.
- Wyswietla ostatnie transakcje.
- Statystyki w dashboardzie sa widoczne.
- Dane dotycza tylko zalogowanego uzytkownika.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Testy przechodza.
- Dokumentacja powstala w docs/F-17-Dashboard.md.
- Kod zgodny ze struktura Laravel.
