# LaravelDemo

A beginner-friendly Laravel application for learning and practicing the core fundamentals of the framework end-to-end (MVC, migrations, seeders, Blade, artisan, Eloquent).

## Tech Stack

- **Language**: PHP ^8.2
- **Framework**: Laravel ^12 (Breeze + Sanctum)
- **Database**: MySQL / MariaDB
- **Frontend**: Vite ^5 + Tailwind CSS 3 + Alpine.js
- **Testing**: PHPUnit ^11 (Mockery, Faker)
- **Linting**: Laravel Pint

## Core Principles

- **Idiomatic Laravel over clever abstractions** — this repo doubles as a learning reference; readability wins.
- **Thin controllers** — validation in Form Requests, business logic in Services, data access in Repositories or Eloquent models.
- **Tests mirror source** — every new feature gets a test under `tests/Unit/` or `tests/Feature/`, structured to mirror `app/`.
- **TDD: Red → Green → Refactor** for non-trivial behavior.
- **Configuration via `config/`, not `env()`** — `env()` only inside config files (cached configs return null otherwise).

## Project Structure

- `app/Http/Controllers/` — thin HTTP handlers; `app/Http/Requests/` — validation.
- `app/Services/` — business logic; `app/Repositories/` — data access; `app/Models/` — Eloquent models.
- `routes/web.php` — session routes, `routes/api.php` — Sanctum-protected, `routes/auth.php` — Breeze.
- `resources/views/` — Blade templates; `resources/js/`, `resources/css/` — Vite-bundled assets.
- `database/migrations|factories|seeders/`; `db/laraveldemo.sql` — full demo dump.
- `tests/Unit/` and `tests/Feature/` — mirror the `app/` layout.

## Commands

```bash
# Install
composer install
npm install

# Run tests
./vendor/bin/phpunit
php artisan test                       # Laravel-flavored runner

# Lint / auto-fix
./vendor/bin/pint --test               # check
./vendor/bin/pint                      # fix

# Dev
npm run dev                            # Vite + HMR
php artisan serve                      # http://127.0.0.1:8000

# Schema
php artisan migrate --seed
php artisan migrate:fresh --seed       # destructive

# Storage symlink (run once after fresh clone)
php artisan storage:link

# Clear caches when something feels off
php artisan optimize:clear
```

## Key Rules

- One class per file; filename matches class name.
- Naming: classes `PascalCase`, methods/vars `camelCase`, constants `SCREAMING_SNAKE_CASE`, tables `plural_snake_case`, columns `snake_case`, routes `kebab-case` URIs with dot-notation names.
- Validation lives in `app/Http/Requests/`; never inline `$request->validate()` for non-trivial rules.
- All Eloquent models declare `$fillable` (never `$guarded = []`).
- Always escape Blade output (`{{ }}`); only use `{!! !!}` after a deliberate review.
- No raw concatenated SQL — Eloquent or query bindings only.
- Never commit `.env`, secrets, or contents of `storage/logs/`.
- Run `./vendor/bin/pint` and `./vendor/bin/phpunit` before every commit.
- New code targets **80% test coverage** for `app/`.
- Mail / Queue / Notifications must be faked in tests (`Mail::fake()`, etc.).

## Detailed Configuration

Project configuration files live in `.claude/`:

- `project-overview.md` — project identity, features, learner-focused framing
- `architecture.md` — directory layout, patterns, request flow
- `testing.md` — test commands, conventions, coverage targets
- `code-standards.md` — naming, linting, security rules
- `pipeline.md` — workflow agents (devil's advocate post-plan, standards-enforcer post-implementation)

## Login (local)
- URL: `http://laraveldemo.local/login`
- Email: `dipakp@logicrays.com`
- Password: `dipak@123`
