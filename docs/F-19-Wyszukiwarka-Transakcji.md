# F-19 - Wyszukiwarka Transakcji

## 1. Opis funkcjonalnosci
Wyszukiwarka pozwala filtrowac liste transakcji po fragmencie opisu na stronie listy transakcji.

## 2. Cel biznesowy
Umozliwienie szybkiego odnalezienia transakcji na podstawie opisu bez potrzeby przegladania calej listy.

## 3. Parametr
- ?search=

## 4. Przyklady URL
- /transactions?search=zakupy

## 5. Integracja z filtrami i sortowaniem
- Wyszukiwanie dziala razem z filtrowaniem, sortowaniem i paginacja.
- Parametr `search` jest zachowywany w polu input po wyszukaniu.

## 6. Test cases
1. Uzytkownik moze wyszukac transakcje po opisie.
2. Wyszukiwanie zwraca poprawne wyniki.
3. Wyszukiwanie dziala tylko na danych uzytkownika.
4. Brak wynikow wyswietla pusta liste.
5. Endpoint wymaga autoryzacji.

## 7. Definition of Done
- Wyszukiwanie po opisie dziala.
- Wyniki sa poprawnie filtrowane.
- Wyszukiwanie dziala z filtrami.
- Wyszukiwanie dziala z sortowaniem.
- Wyszukiwanie dziala z paginacja.
- Dane dotycza tylko zalogowanego uzytkownika.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Testy przechodza.
- Dokumentacja powstala w docs/F-19-Wyszukiwarka-Transakcji.md.
- Kod zgodny ze struktura Laravel.
