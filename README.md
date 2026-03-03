# FinancialApp

Laravel 12 application for user registration flow (`/register`) with Bootstrap 5 + Font Awesome frontend assets built by Vite.  
Project is containerized with Docker Compose (PHP-FPM, Nginx, MySQL, phpMyAdmin).

## Wymagania

- Docker + Docker Compose
- Git
- (Opcjonalnie, poza Dockerem) PHP 8.2+, Composer 2+, Node.js LTS, npm

## Szybki start (TL;DR)

```bash
git clone <REPO_URL>
cd FinancialApp
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app npm install
docker compose exec app npm run build
```

Uruchomienie aplikacji: `http://localhost:8080`.

## Instalacja i uruchomienie lokalne (krok po kroku)

### 1) Klonowanie repo

```bash
git clone <REPO_URL>
cd FinancialApp
```

Opis: Pobiera kod i przechodzi do katalogu projektu.

### 2) Konfiguracja `.env`

```bash
cp .env.example .env
```

Opis: Tworzy lokalny plik konfiguracyjny.  
Uwaga: W tym repo `APP_LOCALE=pl`, `DB_HOST=db`, `DB_PORT=3306`, `QUEUE_CONNECTION=database`, `SESSION_DRIVER=database`, `CACHE_STORE=database`.

Nastepnie wygeneruj klucz aplikacji:

```bash
docker compose exec app php artisan key:generate
```

Opis: Ustawia poprawne `APP_KEY` w `.env`.

### 3) Uruchomienie kontenerow

```bash
docker compose up -d --build
```

Opis: Buduje i uruchamia uslugi z `docker-compose.yml`.

### 4) Instalacja zaleznosci backend (Composer)

```bash
docker compose exec app composer install
```

Opis: Instaluje pakiety PHP z `composer.json`.

### 5) Instalacja zaleznosci frontend (npm)

```bash
docker compose exec app npm install
```

Opis: Instaluje paczki JS/CSS z `package.json`.

### 6) Build frontendu (Vite)

```bash
docker compose exec app npm run build
```

Opis: Buduje assety do `public/frontend/assets` oraz generuje `manifest.json`.

### 7) Migracje i seed

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

Opis: Tworzy tabele (`users`, `sessions`, `jobs`, `cache` itd.) i uruchamia seedery.

### 8) (Opcjonalnie) link storage i cache

```bash
docker compose exec app php artisan storage:link
docker compose exec app php artisan optimize:clear
```

Opis: `storage:link` tylko gdy aplikacja zacznie korzystac z plikow publicznych; `optimize:clear` czysci cache frameworka.

## Dostep / URL-e / Porty

Na podstawie `docker-compose.yml`:

- Aplikacja HTTP (Nginx): `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`
- MySQL host port: `localhost:3306`

Nazwy kontenerow:

- `financialapp_app` (service: `app`, PHP-FPM + Composer + Node)
- `financialapp_web` (service: `web`, Nginx)
- `financialapp_db` (service: `db`, MySQL 8.4)
- `financialapp_pma` (service: `phpmyadmin`)

Wolumen:

- `dbdata` (dane MySQL)

## Najwazniejsze komendy

### A) Docker / Docker Compose

```bash
docker compose up -d --build
```

Buduje obrazy i uruchamia kontenery w tle.

```bash
docker compose down
```

Zatrzymuje i usuwa kontenery/siec utworzone przez Compose.

```bash
docker compose ps
```

Pokazuje status uslug.

```bash
docker compose logs -f
```

Podglad logow wszystkich uslug w czasie rzeczywistym.

```bash
docker compose logs -f app
```

Podglad logow tylko jednej uslugi.

```bash
docker compose exec app <komenda>
```

Wykonanie polecenia w kontenerze aplikacji, np. `php artisan ...`, `composer ...`, `npm ...`.

```bash
docker compose build app --no-cache
```

Pelny rebuild uslugi `app` bez cache.

```bash
docker compose up -d --build app
```

Rebuild i restart tylko uslugi `app`.

```bash
docker system prune
```

Usuwa nieuzywane zasoby Dockera. Uwaga: moze usunac obrazy i cache potrzebne innym projektom.

### B) Laravel (Artisan)

```bash
docker compose exec app php artisan key:generate
```

Generuje `APP_KEY`.

```bash
docker compose exec app php artisan migrate
```

Uruchamia migracje.

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Kasuje wszystkie tabele i odtwarza baze z seederami.

```bash
docker compose exec app php artisan db:seed
```

Uruchamia seedery.

```bash
docker compose exec app php artisan route:list
```

Wyswietla liste tras.

```bash
docker compose exec app php artisan tinker
```

Interaktywna konsola Laravela.

```bash
docker compose exec app php artisan queue:work
docker compose exec app php artisan queue:listen
```

Obsluga kolejek (w `.env` domyslnie `QUEUE_CONNECTION=database`).

```bash
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan cache:clear
```

Budowanie i czyszczenie cache.

```bash
docker compose exec app php artisan storage:link
```

Tworzy link `public/storage`.

```bash
docker compose exec app php artisan test
```

Uruchamia testy (w `phpunit.xml` testy dzialaja na SQLite in-memory).

### C) Frontend (npm)

```bash
docker compose exec app npm install
```

Instaluje zaleznosci frontendowe.

```bash
docker compose exec app npm run dev
```

Uruchamia Vite dev server.

```bash
docker compose exec app npm run build
```

Buduje produkcyjne assety do `public/frontend/assets`.

Do uzupelnienia: brak skryptow `npm run test` i `npm run lint` w `package.json`.

## Typowe problemy (Troubleshooting)

### 1) `No application encryption key has been specified`

```bash
docker compose exec app php artisan key:generate
```

### 2) Blad polaczenia z baza danych

Sprawdz:

- Czy kontener `db` dziala: `docker compose ps`
- Czy `.env` ma `DB_HOST=db`, `DB_PORT=3306`
- Czy migracje sa wykonane: `docker compose exec app php artisan migrate`

### 3) Port 8080/8081/3306 zajety

Zmien mapowania portow w `docker-compose.yml` i uruchom ponownie:

```bash
docker compose down
docker compose up -d --build
```

### 4) Brak stylow/ikon po zmianach frontendu

```bash
docker compose exec app npm run build
```

Sprawdz czy istnieje `public/frontend/assets/manifest.json`.

### 5) Uprawnienia do `storage`/`bootstrap/cache`

W kontenerze:

```bash
docker compose exec app sh -lc "chown -R www-data:www-data storage bootstrap/cache"
docker compose exec app sh -lc "chmod -R ug+rw storage bootstrap/cache"
```

### 6) `php` niedostepny na hoście

W tym projekcie uruchamiaj `php artisan` przez kontener:

```bash
docker compose exec app php artisan <komenda>
```

## Srodowiska (dev/prod) i bezpieczenstwo

### Dev (aktualnie dostepne)

- Dostepny jeden plik Compose: `docker-compose.yml`
- `APP_ENV=local`, `APP_DEBUG=true` (w `.env.example`)
- W projekcie jest `composer` script `dev` (lokalny multitool: `artisan serve`, queue, logs, vite), ale w tym repo glowna sciezka to Docker.

### Prod

Do uzupelnienia:

- Brak dedykowanego `docker-compose.prod.yml` lub innej jawnej konfiguracji production w repo.
- Brak opisanych procedur CI/CD, release i rollback.

### Bezpieczenstwo

- Nie commituj prywatnych hasel i kluczy do `.env` (w tym repo `.env.example` zawiera wartosci, ktore nalezy zweryfikowac przed publikacja).
- W production ustaw `APP_DEBUG=false`.
- Dla production uzyj cache:
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`

## Architektura (skrot)

Przeplyw:

`Browser -> Nginx (service web) -> PHP-FPM (service app) -> MySQL (service db)`

Dodatkowo:

- Frontend assety budowane przez Vite w kontenerze `app`.
- phpMyAdmin jako narzedzie administracyjne DB.

## Checklista po starcie

- [ ] `docker compose ps` pokazuje wszystkie uslugi jako `Up`
- [ ] `http://localhost:8080/register` dziala
- [ ] `docker compose exec app php artisan migrate` wykonuje sie bez bledow
- [ ] `docker compose exec app npm run build` tworzy aktualne assety
- [ ] `docker compose exec app php artisan test` przechodzi

