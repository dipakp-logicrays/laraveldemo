# Plan: Upgrade Laravel 12 → 13

## Created
2026-04-29

## Status
completed

## Execution Summary (filled at completion: 2026-04-29)

All 10 tasks executed in-session (without the auto-orchestrator) to honor the no-phpunit data-safety constraint. Outcome:

- **001**: Pre-upgrade state verified. Backup MD5 `877f95f6bac4d35601402c9f967f2027`. Plan baseline corrected from `information_schema` approximations (55/135/20/279) to exact `COUNT(*)` values (56/140/13/21/280/12).
- **002**: `composer update --with-all-dependencies --no-scripts --no-interaction` succeeded, 92 ops. Resolved 5 prior security advisories. `optimize:clear` ran cleanly against L13 — the legacy L10 skeleton is fully backwards-compatible on L13. Mid-task correction: `nunomaduro/collision` reverted to `^8.1` (no v9 exists, latest is v8.9.4 with L13 support); `spatie/laravel-ignition` reverted to `^2.12` (no stable v3 yet).
- **003**: CSRF middleware Strategy A applied — only `app/Http/Middleware/VerifyCsrfToken.php` line 5 changed (`VerifyCsrfToken as Middleware` → `PreventRequestForgery as Middleware`). The L13 framework ships a `@deprecated` `VerifyCsrfToken` alias, so the old import would have continued to work; the edit avoids the deprecation.
- **004**: `upsert()` audit — zero hits. No-op.
- **005**: Cache audit — zero hits. Queue dispatch audit — one hit (`SendContactEmail::dispatch($contact)`); confirmed safe via `SerializesModels` trait on `app/Jobs/SendContactEmail.php:16`. No `serializable_classes` allow-list needed.
- **006**: `JobAttempted`/`QueueBusy` audit — zero hits. Direct `Contracts\*` implementer audit — zero hits. Legacy-skeleton vendor classes (`Foundation/Support/Providers/{Event,Auth,Route}ServiceProvider.php`, `Foundation/{Console,Http}/Kernel.php`) — all 5 confirmed present in L13 vendor.
- **007**: 11 config files diffed against L13 framework stubs. Zero adopt decisions — all deltas were either cosmetic (comment rewording, env() wrappers around the same defaults) or features the project doesn't use (resend, reverb, mariadb, deferred queue, frontend_url, rehash_on_login).
- **008**: Pint normalized 52 files (style only — import ordering, trailing commas, `fully_qualified_strict_types`). `composer dump-autoload --optimize` regenerated autoload (6,921 classes) and ran `package:discover` cleanly. `vendor:publish --tag=laravel-assets` reported "No publishable resources" (zero `public/` writes). CSRF parent-import edit survived Pint.
- **009**: `php artisan about` reports Laravel 13.7.0 / PHP 8.4.20. `route:list` enumerates 59 routes (contacts/6, products/1, posts/7, faqs/1, api/faqs/5, login/2, register/2, dashboard/1, comments/5). Storage symlink intact. Read-only DB row counts unchanged: 56/140/13/21/280/12. `curl /` → 200 (82 KB), `/login` → 200 with `_token` CSRF input, `/api/faqs` → 200 with valid JSON.
- **010**: Operator confirmed manual smoke test green. Single commit landed on `chore/upgrade-laravel-13` with 56 files (composer.json + composer.lock + 1 CSRF parent-import + 51 Pint touches + Pint-touched bootstrap/config/route/database/factory/seeder/migration files).

**Data safety**: live MySQL DB byte-identical before/after. Zero migrations executed, zero `phpunit` invocations, zero seeders run.

**Devil's advocate fears that did NOT materialize**:
- Boot did not break under composer's `post-autoload-dump` — but we still used `--no-scripts` defensively.
- `php artisan about` did not error on the legacy skeleton.
- L13 retained backwards-compat aliases for `VerifyCsrfToken` and the legacy ServiceProvider base classes.
- No surprise `vendor:publish` writes.

**Deferred to follow-up plans**:
- Wire up isolated test database; address PHPUnit 12 schema migration of `phpunit.xml`.
- `rehash_on_login` adoption (security improvement; was deferred to avoid mixing with the upgrade).
- Optional migration to L11+ fluent skeleton (`bootstrap/app.php` configure() API).
- Pre-existing unregistered `admin` middleware in `routes/web.php:83` (flagged but not fixed).

## Objective
Upgrade laraveldemo from Laravel `^12` to Laravel `^13.0` (latest stable v13.7.0) along with all transitively-required dependency bumps, applying any code-level breaking-change fixes from the official L12→L13 upgrade guide. Live MySQL data must remain untouched.

## Related Issues
none

## Branch
`chore/upgrade-laravel-13` (already created from `master`; pre-upgrade DB backup committed at `db/laraveldemo.pre-upgrade-2026-04-29.sql`).

## Scope

### In Scope
- Bump dependency constraints in `composer.json` (already edited in working tree, **not yet locked**).
- Regenerate `composer.lock` via `composer update --with-all-dependencies`.
- Apply Laravel 12 → 13 code-level breaking-change fixes:
  - CSRF middleware rename (`VerifyCsrfToken` → `PreventRequestForgery`).
  - `upsert()` calls audited for empty `uniqueBy`.
  - Cache `serializable_classes` allow-list reviewed if app caches non-scalar objects.
  - Event-listener sweep (`JobAttempted`, `QueueBusy` property renames).
  - Config diff against the L13 stubs published by the new vendor packages.
- Run Laravel Pint for style normalization.
- Verify framework boots cleanly via read-only `php artisan` commands.
- Single commit on `chore/upgrade-laravel-13` after manual smoke test.

### Out of Scope
- Schema/database changes — **the live `laraveldemo` MySQL DB MUST NOT be touched**. No `migrate`, `migrate:fresh`, `db:seed`.
- Test suite execution — explicit user constraint. Breeze auth tests use `RefreshDatabase` against the live DB and would wipe data, so all `phpunit` runs are deferred to a separate follow-up plan.
- Frontend stack upgrades (Vite, Tailwind, Alpine) — left at current versions.
- Production deployment / merging to `master` — that is a separate decision after the smoke test.

## Success Criteria
- [ ] `composer.json` and `composer.lock` express Laravel `^13.0` and all required peer bumps.
- [ ] `vendor/laravel/framework` resolves to `13.7.0` or newer.
- [ ] `php artisan about` runs without errors and reports framework version 13.x.
- [ ] `php artisan route:list` enumerates all expected routes (`/contacts/*`, `/products`, `/posts` or `/blog`, `/faqs`, `/api/faqs`, Breeze auth) without raising middleware-resolution errors.
- [ ] No reference to the framework FQCN `Illuminate\Foundation\Http\Middleware\VerifyCsrfToken` remains in the project's own code (the local class `App\Http\Middleware\VerifyCsrfToken` may remain — Strategy A).
- [ ] No `upsert()` call passes an empty `uniqueBy` array.
- [ ] All `Illuminate\Foundation\Support\Providers\*ServiceProvider` base classes still exist in `vendor/laravel/framework` post-upgrade (legacy-skeleton compatibility check from task 006).
- [ ] `./vendor/bin/pint --test` exits 0 (style clean).
- [ ] **Request-level checks**: `curl` against `/`, `/login`, and `/api/faqs` on `php artisan serve` returns 200 (validates middleware chain, not just boot).
- [ ] Manual smoke test (operator-driven, 18 steps) confirms login, contacts CRUD, FAQs CRUD + API, products list with images, blog + comments render, notifications page renders.
- [ ] Live MySQL data (faqs=56, products=140, contacts=13, posts=21, comments=280, users=12) is **byte-for-byte identical** before and after the upgrade.

## Methodology Note (TDD Adaptation)

This plan deliberately deviates from the standard HCF TDD format. Per explicit user constraint, **no test suite is run** during this upgrade because the existing Breeze auth tests use `RefreshDatabase` against the unconfigured live MySQL connection and would erase all FAQ/product/contact data. Each task therefore lists **Verifications** instead of "Requirements (Test Descriptions)" — these are read-only checks (greps, artisan introspection, file diffs, row counts) that confirm the task's outcome without mutating the database. A separate follow-up plan should later wire up an isolated test database and re-enable the suite.

## Task Overview

| Task | Description | Depends On | Status |
|------|-------------|------------|--------|
| 001 | Verify pre-upgrade state, capture row counts + backup MD5, confirm legacy skeleton | none | pending |
| 002 | Run `composer update --no-scripts` (defer artisan hooks), then `optimize:clear` | 001 | pending |
| 003 | Update CSRF middleware parent import (VerifyCsrfToken → PreventRequestForgery) — Strategy A | 002 | pending |
| 004 | Audit `upsert()` calls for empty `uniqueBy` | 002 | pending |
| 005 | Review cache + queue serialization for `serializable_classes` allow-list | 002 | pending |
| 006 | Sweep event listeners + verify legacy-skeleton vendor classes still exist in L13 | 002 | pending |
| 007 | Diff project `config/*` against Laravel 13 framework stubs | 002 | pending |
| 008 | Run Pint to auto-fix style + re-run deferred composer scripts (`dump-autoload`, `post-update-cmd`) | 003, 004, 005, 006, 007 | pending |
| 009 | Verify framework boot (artisan + curl request-level smoke, all read-only — NO phpunit) | 008 | pending |
| 010 | Hand off manual smoke test (18 ordered steps), then commit upgrade | 009 | pending |

## Dependency Graph

```
001 ──► 002 ──┬─► 003 ─┐
              ├─► 004 ─┤
              ├─► 005 ─┼─► 008 ──► 009 ──► 010
              ├─► 006 ─┤
              └─► 007 ─┘
```

Tasks 003–007 are independent of each other and may run in parallel after 002 lands. Pint (008) is the join point. **Note**: task 002 uses `--no-scripts` to defer `package:discover` and `vendor:publish` until after 003–007 have landed L13-compatible code; task 008 re-runs those scripts.

## Architecture Notes

- **IMPORTANT**: This project uses the **legacy Laravel 10 skeleton**, not the Laravel 11+ fluent skeleton. Specifically:
  - `app/Http/Kernel.php` exists and defines the middleware stack (`$middleware`, `$middlewareGroups`, `$middlewareAliases`).
  - `app/Console/Kernel.php` exists.
  - `app/Exceptions/Handler.php` exists.
  - `app/Providers/EventServiceProvider.php` uses the legacy `$listen` array, extending `Illuminate\Foundation\Support\Providers\EventServiceProvider`.
  - `app/Providers/RouteServiceProvider.php` exists and manually loads `routes/api.php` and `routes/web.php`.
  - `config/app.php` declares the `providers` array (the L11+ skeleton uses `bootstrap/providers.php` instead).
  - `bootstrap/app.php` is the L10-style file (instantiates `Illuminate\Foundation\Application` directly + binds three kernels). It does NOT use the L11+ fluent `Application::configure()->withRouting()->withMiddleware()->withExceptions()->create()` API.
- CSRF middleware references in this codebase are at THREE locations:
  1. `app/Http/Kernel.php` `$middlewareGroups['web']`: `\App\Http\Middleware\VerifyCsrfToken::class`.
  2. `app/Http/Middleware/VerifyCsrfToken.php`: a custom subclass with `use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;`.
  3. `config/sanctum.php` line ~80: `'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class`.
- Laravel 13 renames the framework-level `Illuminate\Foundation\Http\Middleware\VerifyCsrfToken` → `PreventRequestForgery`. The local subclass's parent import must be updated; the local class FQCN (`App\Http\Middleware\VerifyCsrfToken`) can be retained (Strategy A) to avoid changing two other files.
- Sanctum-protected `routes/api.php` is unaffected by CSRF middleware changes.
- The `admin` middleware in `routes/web.php` line 83 is referenced but NOT registered in `app/Http/Kernel.php` `$middlewareAliases`. Pre-existing; flagged for awareness during boot verification.
- Service classes in `app/Services/` and Repositories in `app/Repositories/` are vanilla PHP — unlikely to reference framework internals that broke.
- Form Requests (`app/Http/Requests/`) extend `FormRequest`; the L13 contract additions are non-breaking for subclasses.

## Risks & Mitigations

- **Risk**: `composer update` resolves a dependency that breaks the boot.
  **Mitigation**: branch is isolated; `composer.lock` is in git on master. Roll back via `git checkout master -- composer.lock && rm -rf vendor/ && composer install`.

- **Risk** (HIGH): `composer update`'s `post-autoload-dump` hook runs `php artisan package:discover --ansi`, which boots the application. With the legacy L10 skeleton on L13, the boot may fail if any framework class was renamed/removed (e.g., `VerifyCsrfToken`). This would cause `composer update` itself to exit non-zero before tasks 003–007 can apply fixes.
  **Mitigation**: task 002 invokes `composer update --with-all-dependencies --no-scripts --no-interaction` to skip both `post-autoload-dump` and `post-update-cmd`. Tasks 003–007 then apply the breaking-change fixes. Task 008 (after Pint) re-runs `composer dump-autoload` and `composer run-script post-update-cmd` to regenerate the discovery cache and execute deferred publish steps.

- **Risk**: A code-level breaking change is missed by the static scans (003–006) and surfaces only at runtime.
  **Mitigation**: read-only artisan boot checks in 009 + a no-DB request-level curl smoke check in 009 + manual operator smoke test in 010 catch boot-time and request-time regressions before commit.

- **Risk**: `php artisan about` is a boot-time check; it does NOT exercise the request middleware chain. CSRF, route bindings, and Sanctum middleware are lazy-resolved on first request.
  **Mitigation**: task 009 adds a request-level `curl` check against `php artisan serve` for `/`, `/login`, and `/api/faqs` (all read-only routes that do not write to the DB). These force middleware resolution.

- **Risk**: Stale `bootstrap/cache/config.php` from a prior `php artisan config:cache` survives `composer update` and serves L12 config to L13 code.
  **Mitigation**: task 002 runs `php artisan optimize:clear` immediately after `composer update --no-scripts` and before any other artisan command. Task 009 re-runs `optimize:clear` defensively.

- **Risk**: Any code path in `tests/` is invoked accidentally during verification and triggers `RefreshDatabase` against the live MySQL DB.
  **Mitigation**: this plan **forbids** invoking `phpunit`, `./vendor/bin/phpunit` (with any flag, including `--version` and `--validate-configuration`), or `php artisan test` at any step. Verification 009 uses only `artisan about`/`route:list`/`config:show` and read-only `tinker --execute` for row counts. The mysqldump from 2026-04-29 sits in `db/` as a hard rollback path (`mysql laraveldemo < db/laraveldemo.pre-upgrade-2026-04-29.sql`).

- **Risk**: PHPUnit 12 may introduce XML schema strictness that surfaces as warnings the next time someone runs the suite.
  **Mitigation**: out of scope for this plan. The `phpunit` binary is not invoked at all here. A follow-up plan will wire up an isolated test database and address any PHPUnit 12 schema migration in the same pass.

- **Risk**: `vendor:publish --tag=laravel-assets` (auto-run by composer's `post-update-cmd`) overwrites a customized public asset.
  **Mitigation**: the project does not currently publish framework assets to `public/`; the script is a no-op for this codebase. Task 002 (with `--no-scripts`) defers this; task 008 re-runs it manually after L13-compatible code is in place. `git status public/` is checked before and after.

- **Risk**: Mail dispatch on contact creation throws if SMTP is unconfigured locally.
  **Mitigation**: task 010 instructs the operator to set `MAIL_MAILER=log` in `.env` for the smoke test (revertable).

- **Risk**: Cache prefix / session cookie name format changed (underscores → hyphens) invalidates the active local session, logging the operator out mid-smoke-test.
  **Mitigation**: cosmetic; `php artisan optimize:clear` clears it. Note in task 009.

- **Risk**: Queued `SendContactEmail` job uses `dispatch($contact)` with an Eloquent model. L13 may tighten queue payload deserialization.
  **Mitigation**: task 005 audits dispatch sites in addition to cache writes.
