# F-21 Powiadomienia Toast

## Opis funkcjonalnosci
Globalny system powiadomien Toast wyswietla komunikaty sukcesu i bledu po operacjach w aplikacji. Toast pojawia sie w prawym gornym rogu, nie przesuwa layoutu, znika automatycznie po kilku sekundach i moze byc zamkniety recznie.

## Bootstrap Toast usage
Uzywane sa komponenty Bootstrap:
- `toast`, `toast-container`, `toast-body`, `btn-close`
- pozycjonowanie: `position-fixed top-0 end-0 p-3`

Toast ma ustawiony `data-bs-delay="5000"` i jest inicjalizowany w JS.

## Laravel flash session
Komunikaty pochodza z Laravel flash session, np.:
`return redirect()->route('transactions.index')->with('success', 'Transakcja zostala dodana.');`

Obslugiwane typy:
- `success`
- `error`

## Integracja JS
W `frontend/assets/js/app.js` inicjalizowany jest Toast dla wszystkich elementow `.toast` po zaladowaniu DOM:
`new bootstrap.Toast(toastEl).show();`

## UX zachowanie
- Toast pojawia sie w prawym gornym rogu.
- Toast jest ponad interfejsem i nie przesuwa layoutu.
- Auto-hide po 5000 ms.
- Uzytkownik moze zamknac Toast recznie.

## Test cases
Plik: `tests/Feature/System/SystemToastNotificationTest.php`
- Toast sukcesu po dodaniu transakcji.
- Toast sukcesu po edycji transakcji.
- Toast sukcesu po usunieciu transakcji.
- Toast znika po odswiezeniu strony (flash session).
- Endpointy transakcji wymagaja autoryzacji.

## Definition of Done
- Toast notifications dzialaja globalnie.
- Komunikaty sukces/blad wyswietlaja sie poprawnie.
- Toast pojawia sie w prawym gornym rogu.
- Toast znika automatycznie po 5 sekundach.
- Toast mozna zamknac recznie.
- Bootstrap toast dziala poprawnie.
- FontAwesome ikony dzialaja.
- Zgodnosc z Vite oraz struktura katalogow bez zmian.
- Dokumentacja znajduje sie w `docs/F-21-Powiadomienia-Toast.md`.
