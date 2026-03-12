# F-13 - Podsumowanie Finansowe

## 1. Opis funkcjonalnosci
Strona podsumowania finansowego prezentuje sume przychodow, sume wydatkow oraz aktualne saldo zalogowanego uzytkownika.

## 2. Cel biznesowy
Umozliwienie szybkiego wgladu w kondycje finansowa uzytkownika i biezuce saldo.

## 3. Endpoint
- GET /reports/summary

## 4. Logika obliczen
- Przychody: suma transakcji o typie income dla zalogowanego uzytkownika.
- Wydatki: suma transakcji o typie expense dla zalogowanego uzytkownika.
- Saldo: przychody minus wydatki.

## 5. Struktura widoku
- 3 karty Bootstrap (card, card-body, shadow-sm).
- Sekcje: Suma przychodow, Suma wydatkow, Saldo.
- Ikony FontAwesome: fa-arrow-up, fa-arrow-down, fa-wallet.

## 6. Test cases
1. Uzytkownik widzi sume przychodow.
2. Uzytkownik widzi sume wydatkow.
3. Saldo oblicza sie poprawnie.
4. Dane dotycza tylko zalogowanego uzytkownika.
5. Endpoint wymaga autoryzacji.

## 7. Definition of Done
- Strona podsumowania dziala.
- Suma przychodow jest poprawna.
- Suma wydatkow jest poprawna.
- Saldo jest poprawnie obliczone.
- Dane dotycza tylko uzytkownika.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Testy przechodza.
- Dokumentacja powstala w docs/F-13-Podsumowanie-Finansowe.md.
- Kod zgodny ze struktura Laravel.
