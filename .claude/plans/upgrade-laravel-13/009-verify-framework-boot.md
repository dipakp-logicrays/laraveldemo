# Task 009: Verify framework boot via read-only artisan commands

**Status**: pending
**Depends on**: 008
**Retry count**: 0

## Description
Confirm Laravel 13 boots, the route/config trees resolve, and request-level middleware (CSRF, Sanctum, route bindings) actually fires — using only operations that do not touch the database and that do **not** invoke any test tooling. Per project constraint, **no `migrate`, `migrate:fresh`, `db:seed`, no `phpunit` invocation (any flag), and no `php artisan test` is permitted**.

This task adds a **request-level** check via `php artisan serve` + `curl` against read-only routes, which is essential because `php artisan about` is a boot-time-only check and does not exercise the lazily-resolved middleware stack. The curl checks are **operational smoke pings**, not a test suite — they hit publicly-rendered GET routes and assert HTTP status only.

## Context
- `php artisan optimize:clear` is safe: it only clears `bootstrap/cache/*`, `storage/framework/*`, and the file-based session/cache directories. It does **not** touch the database.
- `php artisan about` enumerates installed package versions and environment.
- `php artisan route:list` resolves every registered route — surfaces middleware/binding errors.
- `php artisan config:show <key>` is read-only.
- `php artisan tinker --execute='...'` may be used for read-only DB row counts.
- `php artisan serve` starts a local dev server on port 8000; `curl` against it exercises the full middleware chain. Read-only routes (`/`, `/login` GET, `/api/faqs`) do not write to the DB. Stop the server with Ctrl-C or `pkill` when done.
- The `phpunit` binary (any flag) is **forbidden** in this task. A follow-up plan will revisit testing once an isolated test DB exists.

## Verifications
- [ ] `php artisan optimize:clear` runs without error.
- [ ] `php artisan about` runs without error and reports `Laravel Framework: 13.x.x` and `PHP Version: 8.x.x` (≥ 8.3).
- [ ] `php artisan route:list --no-ansi` runs without error and lists at least the expected route prefixes (`/contacts`, `/products`, `/blog` or `/posts`, `/faqs`, `/login`, `/register`, `/dashboard`, `/api/faqs`).
- [ ] **Middleware alias resolution**: `php artisan route:list --no-ansi` does not emit any "middleware [admin] not found" or similar errors. (Note: `routes/web.php` line 83 references an `admin` middleware that is NOT in `app/Http/Kernel.php` `$middlewareAliases`. Pre-existing; flag in Implementation Notes if the resolver complains, but do not fix in this plan.)
- [ ] `php artisan config:show app` runs without error.
- [ ] `php artisan config:show database.connections.mysql` runs without error and shows the MySQL connection.
- [ ] **PHPUnit binary is NOT invoked in this task** (deferred to a follow-up plan along with isolated test-DB setup). Confirm the executor never runs `./vendor/bin/phpunit`, `phpunit`, `php artisan test`, or any wrapper.
- [ ] **Storage symlink intact**: `test -L public/storage` exits 0. If not, run `php artisan storage:link` (this is a filesystem-only operation, does not touch the DB).
- [ ] Read-only DB sanity (no schema change): `php artisan tinker --execute="echo DB::table('faqs')->count();"` returns **56**, `products` returns **140**, `contacts` returns **13**, `posts` returns **21**, `comments` returns **280**, `users` returns **12**. Live data is intact (corrected baseline per task 001 execution; original plan used information_schema approximations). If `tinker --execute` syntax changed in Tinker 3, fall back to a one-shot PHP script that boots the framework and reads counts — do NOT use any command that may write.
- [ ] **Request-level smoke (catches lazy middleware errors that `about` cannot)**:
  - Start `php artisan serve --port=8765` in the background (or in a separate terminal).
  - `curl -sS -o /dev/null -w '%{http_code}' http://127.0.0.1:8765/` returns `200`.
  - `curl -sS -o /dev/null -w '%{http_code}' http://127.0.0.1:8765/login` returns `200`.
  - `curl -sS -o /dev/null -w '%{http_code}' http://127.0.0.1:8765/api/faqs` returns `200` and `curl -sS http://127.0.0.1:8765/api/faqs | head -c 200` shows JSON content (not an HTML error page).
  - Stop the dev server.
  - These three GET routes are **read-only** — they call `select` queries only. No data is mutated.
- [ ] `git status --short` shows the expected diff (composer.json, composer.lock, plus any code edits from 003–007 and Pint touches) — and **nothing** under `database/migrations/` or `db/`.

## Acceptance Criteria
- All boot-time, request-level, and read-only DB checks pass.
- Live DB row counts match the pre-upgrade baseline.
- If any artisan command or curl check fails (non-200 or HTML error response on a route that should be 200), **stop the plan** and surface the stack trace from `storage/logs/laravel.log`; do not proceed to 010.

## Implementation Notes
*(left blank — filled by the executor)*
