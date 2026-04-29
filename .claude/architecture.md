# Architecture

## Directory Structure

```
app/
  Console/Commands/         Artisan commands
  Http/
    Controllers/            HTTP request handlers (thin)
    Middleware/             Request middleware
    Requests/               Form Request validation classes
  Models/                   Eloquent models
  Providers/                Service providers
  Services/                 Business logic services (where present)
  Repositories/             Repository pattern (where used for data access)
bootstrap/                  Framework bootstrap
config/                     Application config
database/
  factories/                Model factories (test data)
  migrations/               Schema migrations
  seeders/                  Database seeders
public/                     Web root (index.php, compiled assets, storage symlink)
resources/
  views/                    Blade templates
  js/                       Frontend JS (Alpine.js entry points)
  css/                      Tailwind stylesheets
routes/
  web.php                   Web (session) routes
  api.php                   Stateless API routes (Sanctum-protected)
  auth.php                  Breeze auth routes
storage/
  app/public/               User-uploaded files (symlinked to public/storage)
  framework/                Cache, sessions, views
  logs/                     Application logs
tests/
  Unit/                     Unit tests (mirror app/ structure)
  Feature/                  Integration / HTTP feature tests
db/                         SQL dump of the demo database (laraveldemo.sql)
databases_backup/           Manual database snapshots
API-Documents/              API documentation references
```

## Patterns Used

- **Standard MVC** — controllers receive requests, models represent data, Blade views render output. This is the dominant pattern for the demo features.
- **Service classes** (`app/Services/`) — heavier business logic (e.g., contact email dispatch, FAQ syncing) is extracted from controllers into Services. Inject services via the constructor.
- **Repository pattern** (`app/Repositories/`) — data access for non-trivial queries is wrapped behind repository classes/interfaces. Bind interfaces to concrete implementations in a service provider.
- **Form Requests** (`app/Http/Requests/`) — all controller-level validation lives in Form Request classes. Controllers should typehint the Form Request and call `$request->validated()`.

## Conventions

- **One class per file**, filename matches class name.
- **Tests mirror source structure** under `tests/Unit/` and `tests/Feature/`.
- **Controllers stay thin** — orchestrate input, delegate work, return a response.
- **Routes are explicit** — prefer named routes; group by feature; avoid magic implicit binding when explicit binding is clearer.
- **Migrations are the source of truth** for schema. Reseed via `php artisan migrate:fresh --seed` when schema changes.
- **Frontend assets** are bundled by Vite (`vite.config.js` + `resources/js/app.js`). Use the `@vite` Blade directive in layouts.
- **Authentication scaffolding** comes from Laravel Breeze; do not hand-roll login/registration views — extend the Breeze stubs.
- **API requests** authenticate via Sanctum personal access tokens or SPA cookie-based auth; CSRF middleware applies on web routes only.
- **Email** is dispatched via Mailables under `app/Mail/`; in tests use `Mail::fake()`.

## Key Integrations

- **Database**: MySQL / MariaDB. Schema can be bootstrapped from `db/laraveldemo.sql` or via `php artisan migrate --seed`.
- **Mail**: Configured via `MAIL_*` env vars. Contact form triggers an email on submission.
- **Sanctum**: Provides API authentication for the FAQs API consumer.
- **Storage symlink**: `php artisan storage:link` is required after fresh installs so uploaded images are reachable from `public/storage/`.

## Bootstrap & Configuration

- `bootstrap/app.php` is the application entry — review it before changing service registration.
- `config/` is the canonical location for tunable settings; never read `env()` outside config files (Laravel best practice — `env()` returns null when configs are cached).
- Run `php artisan config:cache` / `php artisan route:cache` only in production; clear them in dev with `php artisan optimize:clear`.

## Error Handling

- Application errors surface via `spatie/laravel-ignition` in development and `nunomaduro/collision` in CLI output.
- Logs land in `storage/logs/laravel.log` by default.
- Exceptions thrown from Services should be domain-specific (custom exception classes) so HTTP layer can map them cleanly.

## Request Flow

```
Request → Route → Middleware → Form Request (validation)
       → Controller (orchestrator)
       → Service (business logic)  ← optional
       → Repository / Eloquent Model (data access)
       → Database
       ← Model / DTO
       ← View (Blade) or JsonResponse
       → Response
```
