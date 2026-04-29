# Task 003: Apply CSRF middleware rename (VerifyCsrfToken → PreventRequestForgery)

**Status**: pending
**Depends on**: 002
**Retry count**: 0

## Description
Laravel 13 renames the framework's CSRF middleware from `Illuminate\Foundation\Http\Middleware\VerifyCsrfToken` to `Illuminate\Foundation\Http\Middleware\PreventRequestForgery` and adds a `Sec-Fetch-Site` origin check. This project uses the **legacy L10 skeleton**, so the references live in three files (NOT in `bootstrap/app.php`).

**Strategy: A (least invasive)** — keep the local class name `App\Http\Middleware\VerifyCsrfToken` (so `app/Http/Kernel.php` and `config/sanctum.php` need no edit). Only update the **parent class import** inside `app/Http/Middleware/VerifyCsrfToken.php` from `VerifyCsrfToken` to `PreventRequestForgery`.

## Context
- Files known to reference `VerifyCsrfToken` (verified pre-plan):
  1. `app/Http/Middleware/VerifyCsrfToken.php` — `use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;` (line 5).
  2. `app/Http/Kernel.php` — line 37, `\App\Http\Middleware\VerifyCsrfToken::class` in the `web` middlewareGroup (this is the LOCAL class, no edit needed under Strategy A).
  3. `config/sanctum.php` — line ~80, `'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class` (LOCAL class, no edit needed under Strategy A).
- Old framework class: `Illuminate\Foundation\Http\Middleware\VerifyCsrfToken`.
- New framework class: `Illuminate\Foundation\Http\Middleware\PreventRequestForgery`.
- If Laravel 13 still ships a backwards-compat alias for `VerifyCsrfToken`, no edit is strictly required; the edit is defensive.

## Verifications
- [ ] `grep -rn 'VerifyCsrfToken' bootstrap/ app/ config/ routes/ resources/ 2>/dev/null` lists exactly three hits (the three files above) **before** edits.
- [ ] Confirm `vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestForgery.php` exists post-`composer update`. If it does NOT exist, abort task 003 and surface the L13 release notes — the rename strategy may differ.
- [ ] Edit `app/Http/Middleware/VerifyCsrfToken.php`: change `use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;` to `use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as Middleware;`. The class name and namespace stay as `App\Http\Middleware\VerifyCsrfToken`.
- [ ] After edit: `php -l app/Http/Middleware/VerifyCsrfToken.php` exits 0.
- [ ] After edit: `grep -rn 'Illuminate\\\\Foundation\\\\Http\\\\Middleware\\\\VerifyCsrfToken' bootstrap/ app/ config/ routes/ resources/ 2>/dev/null` returns no results — the only remaining `VerifyCsrfToken` matches reference the LOCAL class.
- [ ] `php artisan about` (run after edits) does not error. **Note**: this is a boot-time check only; it does not exercise the CSRF middleware. The request-level smoke check in task 009 is the runtime confirmation.
- [ ] `php artisan route:list` runs without throwing.

## Acceptance Criteria
- The local subclass `App\Http\Middleware\VerifyCsrfToken` extends the new framework parent (`PreventRequestForgery`).
- No project-owned file imports the old framework FQCN.
- Local class FQCN is unchanged, so `app/Http/Kernel.php` and `config/sanctum.php` need no edits (Strategy A).
- If the L13 framework retained a backwards-compat alias and the existing import still works, the edit is still applied (defensive, prevents future deprecation warnings).
- If Strategy A turns out to be insufficient (e.g., L13 has a hard rename without alias and somehow surfaces at runtime), fall back to Strategy B: rename the local class to `App\Http\Middleware\PreventRequestForgery` and update `app/Http/Kernel.php` line 37 + `config/sanctum.php` line 80 in the same edit. Document the choice in Implementation Notes.

## Implementation Notes
*(left blank — filled by the executor)*
