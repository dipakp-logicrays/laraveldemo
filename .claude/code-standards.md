# Code Standards

## Style Guide
- **PHP**: PSR-12 baseline, enforced by Laravel Pint with the default `laravel` preset.
- **JavaScript**: Vanilla / Alpine.js — keep scripts minimal and idiomatic; no project-wide JS style tool is configured.
- **Blade**: Two-space indentation, one directive per line, prefer components / partials in `resources/views/components/` for reuse.

## Linting

```bash
# Check for issues (no changes written)
./vendor/bin/pint --test

# Auto-fix issues
./vendor/bin/pint

# Lint a specific path
./vendor/bin/pint app/Http/Controllers
```

## Formatting
Pint handles formatting in addition to linting; there is no separate formatter for PHP. Editor-side defaults come from `.editorconfig`.

## Pre-commit Checks
Before committing:
1. `./vendor/bin/pint` — auto-fix style.
2. `./vendor/bin/phpunit` — all tests must pass.
3. Review the diff — no debug `dd()` / `var_dump()` / `console.log` left behind.
4. No committed `.env` changes; secrets stay local.

## Naming Conventions
- **Classes**: `PascalCase` (e.g., `ProductController`, `ContactService`).
- **Methods / functions**: `camelCase` (e.g., `storeContact`, `sendNotification`).
- **Variables / properties**: `camelCase` (e.g., `$productList`).
- **Constants**: `SCREAMING_SNAKE_CASE` (e.g., `MAX_UPLOAD_SIZE`).
- **Database tables**: plural `snake_case` (e.g., `products`, `contact_messages`).
- **Database columns**: `snake_case` (e.g., `created_at`, `user_id`).
- **Routes**: kebab-case URIs, dot-notation route names (e.g., `Route::get('/contacts/create', ...)->name('contacts.create')`).
- **Migration files**: timestamp + verb (e.g., `2025_05_01_120000_create_products_table.php`).
- **Blade view files**: kebab-case (e.g., `resources/views/contacts/index.blade.php`).
- **Eloquent models**: singular `PascalCase` (e.g., `Product`, `Contact`).

## Code Structure Rules
- One class per file; filename matches class name.
- Controllers stay thin — delegate to Services for business logic.
- Validation belongs in `app/Http/Requests/` Form Request classes, not inline in controllers.
- Database access in repositories or Eloquent models — controllers should not write raw queries.
- Mass assignment must be guarded via `$fillable` (never use `$guarded = []` in production models).
- Use route model binding wherever possible.
- Prefer dependency injection over `app()` / `resolve()` calls.

## Documentation Standards
- Public methods on Services and Repositories should carry a one-line PHPDoc when behavior is non-obvious.
- Prefer expressive names and types over comments. Only comment **why** (constraints, gotchas), never **what** the code already says.
- Migrations: include both `up()` and `down()` paths; never silently rely on missing `down()`.

## Security Rules
- Never trust user input — validate via Form Requests.
- Always escape output in Blade (`{{ }}`); only use `{!! !!}` after a deliberate review.
- Use Eloquent / query bindings — no raw concatenated SQL.
- Authentication: route through Breeze + Sanctum; gate authorization with Policies / Gates.
- Mass assignment safety: `$fillable` allowlist on every model.
- File uploads: validate MIME + size, store under `storage/app/public` and serve via `php artisan storage:link`.
