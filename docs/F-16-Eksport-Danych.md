# F-16 - Eksport Danych

## 1. Opis funkcjonalnosci
Strona eksportu umozliwia pobranie wszystkich transakcji zalogowanego uzytkownika w formacie CSV lub JSON.

## 2. Cel biznesowy
Latwe przenoszenie danych i archiwizacja transakcji przez uzytkownika.

## 3. Endpointy
- GET /export
- GET /export/csv
- GET /export/json

## 4. Format CSV
- Naglowki: Date,Amount,Type,Category,Description
- Dane: data transakcji, kwota, typ, kategoria, opis

## 5. Format JSON
- Zwracana jest kolekcja transakcji uzytkownika wraz z relacja category.
- Naglowek Content-Disposition wymusza pobranie pliku.

## 6. Scenariusze uzycia
1. Uzytkownik wchodzi na strone eksportu.
2. Wybiera format CSV lub JSON.
3. Plik jest pobierany na komputer.

## 7. Test cases
1. Uzytkownik moze pobrac CSV.
2. Uzytkownik moze pobrac JSON.
3. Plik zawiera tylko dane uzytkownika.
4. Endpoint wymaga autoryzacji.
5. CSV zawiera poprawne kolumny.

## 8. Definition of Done
- Strona eksportu dziala.
- Uzytkownik moze pobrac CSV.
- Uzytkownik moze pobrac JSON.
- Dane dotycza tylko zalogowanego uzytkownika.
- Pliki maja poprawny format.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Testy przechodza.
- Dokumentacja powstala w docs/F-16-Eksport-Danych.md.
- Kod zgodny ze struktura Laravel.
