# F-24 - Landing Page

## 1. Opis funkcjonalnosci
Publiczna strona glowna aplikacji prezentuje jej mozliwosci oraz prowadzi do logowania i rejestracji.

## 2. Struktura strony
- Hero z naglowkiem, opisem i CTA
- Sekcja opisu aplikacji
- Lista funkcjonalnosci w kartach
- Sekcja ponownego CTA

## 3. Lista sekcji landing page
- Hero (display-4, lead, przyciski logowania i rejestracji)
- Opis aplikacji (krotki opis korzysci)
- Funkcjonalnosci (grid kart)
- CTA na koncu strony

## 4. Integracja z routingiem
- GET / -> LandingController@index
- Dla zalogowanych przekierowanie do /dashboard

## 5. Test cases
1. Strona glowna laduje sie poprawnie.
2. Strona zawiera link do logowania.
3. Strona zawiera link do rejestracji.
4. Strona jest dostepna dla niezalogowanych uzytkownikow.

## 6. Definition of Done
- Landing page dziala pod adresem /.
- Strona zawiera opis aplikacji.
- Przyciski logowania i rejestracji dzialaja.
- Bootstrap styling poprawny.
- FontAwesome ikony dzialaja.
- Strona jest responsywna.
- Testy przechodza.
- Dokumentacja powstala w docs/F-24-Landing-Page.md.
- Kod zgodny ze struktura Laravel.
