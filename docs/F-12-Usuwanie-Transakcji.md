# F-12 - Usuwanie Transakcji

## 1. Opis funkcjonalnosci
Uzytkownik moze usuwac swoje transakcje z lista i otrzymuje potwierdzenie przed wykonaniem operacji. Usuwanie jest realizowane jako soft delete.

## 2. Cel biznesowy
Zapewnienie bezpiecznego usuwania transakcji bez utraty danych historycznych oraz mozliwosc przywrocenia danych w przyszlosci.

## 3. Endpoint
- DELETE /transactions/{transaction}

## 4. Mechanizm Soft Delete
- Rekordy nie sa usuwane trwale.
- Kolumna deleted_at przechowuje timestamp usuniecia.
- Lista transakcji nie pokazuje rekordow usunietych (bez withTrashed).

## 5. Przyklad formularza
```
<form method="POST" action="{{ route('transactions.destroy', $transaction->id) }}" onsubmit="return confirm('Czy na pewno chcesz usunac te transakcje?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-outline-danger">
        <i class="fa-solid fa-trash"></i>
        Usun
    </button>
</form>
```

## 6. Scenariusz dzialania
1. Uzytkownik klika przycisk Usun.
2. Pojawia sie potwierdzenie.
3. Po potwierdzeniu transakcja jest oznaczana jako usunieta.
4. Uzytkownik wraca na liste z komunikatem sukcesu.

## 7. Test cases
1. Uzytkownik moze usunac swoja transakcje.
2. Rekord ma ustawione deleted_at.
3. Usunieta transakcja nie pojawia sie na liscie.
4. Uzytkownik nie moze usunac cudzej transakcji.
5. Endpoint wymaga autoryzacji.

## 8. Definition of Done
- Uzytkownik moze usunac transakcje.
- Pojawia sie potwierdzenie przed usunieciem.
- Rekord uzywa soft delete.
- Usuniete transakcje nie pojawiaja sie na liscie.
- Uzytkownik moze usunac tylko swoje transakcje.
- Bootstrap styling poprawny.
- FontAwesome dziala.
- Testy przechodza.
- Dokumentacja powstala w docs/F-12-Usuwanie-Transakcji.md.
- Kod zgodny ze struktura Laravel.
