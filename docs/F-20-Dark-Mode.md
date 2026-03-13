# F-20 - Dark/Light Mode

## 1. Opis funkcjonalnosci
Tryb jasny/ciemny pozwala uzytkownikowi przelaczyc wyglad calej aplikacji i zapisuje preferencje w przegladarce.

## 2. Wykorzystanie Bootstrap dark mode
Bootstrap 5.3 wspiera dark mode przez atrybut `data-bs-theme` ustawiany na elemencie `<html>`.

## 3. Mechanizm data-bs-theme
- `data-bs-theme="light"` dla trybu jasnego.
- `data-bs-theme="dark"` dla trybu ciemnego.

## 4. Przechowywanie preferencji
Preferencja jest zapisywana w `localStorage` pod kluczem `theme`.

## 5. Dzialanie JavaScript
- Odczyt zapisanej preferencji z `localStorage`.
- Gdy brak zapisu, wybierany jest domyslny tryb na podstawie systemu.
- Przelacznik w navbarze zmienia `data-bs-theme` i zapisuje nowa wartosc.
- Ikona przycisku zmienia sie automatycznie.

## 6. Test cases
1. Klikniecie przycisku zmienia tryb.
2. Ikona zmienia sie poprawnie.
3. Preferencja zapisuje sie w localStorage.
4. Po odswiezeniu strony tryb pozostaje.
5. Tryb dziala globalnie w calej aplikacji.

## 7. Definition of Done
- Przelacznik dark/light dziala.
- Tryb dziala globalnie w aplikacji.
- Preferencja zapisywana w localStorage.
- Ikona zmienia sie poprawnie.
- Bootstrap dark mode dziala.
- Kompatybilnosc z Vite.
- Brak bledow JS.
- Dokumentacja powstala w docs/F-20-Dark-Mode.md.
- Kod zgodny ze struktura Laravel.
