# F-09 - Filtrowanie Transakcji

## 1. Cel funkcjonalnosci
Umozliwienie filtrowania listy transakcji po zakresie dat, typie oraz kategorii. Filtry dzialaja lacznie (kombinacja) i sa przekazywane w query string.

## 2. Parametry GET
- date_from (YYYY-MM-DD)
- date_to (YYYY-MM-DD)
- type (income | expense)
- category_id (ID kategorii uzytkownika)

## 3. Walidacja
- date_from: nullable | date
- date_to: nullable | date | after_or_equal:date_from
- type: nullable | in:income,expense
- category_id: nullable | exists:categories,id (user_id = auth)

## 4. Przykladowy URL
- /transactions?type=expense&date_from=2025-01-01

## 5. Test cases
1. Filtrowanie po typie dziala.
2. Filtrowanie po zakresie dat dziala.
3. Filtrowanie po kategorii dziala.
4. Kombinacja filtrow dziala.
5. Uzytkownik nie moze filtrowac cudzych kategorii.
6. date_to < date_from zwraca blad walidacji.

## 6. Definition of Done
- Filtrowanie dziala po zakresie dat, typie i kategorii.
- Filtry mozna laczyc.
- Paginacja zachowuje query string.
- Walidacja dziala poprawnie.
- Middleware auth dziala.
- Brak dostepu do cudzych danych.
- Testy przechodza.
- Bootstrap + FontAwesome dzialaja.
- Dokumentacja zaktualizowana.
- Kod zgodny ze struktura Laravel.
