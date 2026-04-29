# Task 007: Diff project `config/*` against Laravel 13 framework stubs

**Status**: pending
**Depends on**: 002
**Retry count**: 0

## Description
Laravel 13 ships small adjustments to several config defaults (cache prefixes, session cookie naming, broadcasting connection keys). Rather than blindly running `php artisan config:publish`, diff each project config file against the equivalent file shipped under `vendor/laravel/framework/config/` and surface only the keys where the L13 default has changed. The operator decides which deltas to adopt.

## Context
- Project configs: `config/app.php`, `config/auth.php`, `config/cache.php`, `config/database.php`, `config/filesystems.php`, `config/logging.php`, `config/mail.php`, `config/queue.php`, `config/services.php`, `config/session.php`, `config/sanctum.php`.
- L13 stubs after `composer update`: `vendor/laravel/framework/config/*.php`.
- Notable expected deltas (per upgrade guide):
  - Cache prefix and session cookie names changed underscores → hyphens.
  - `config/cache.php` may have a new `serializable_classes` key (covered in 005).
  - `config/queue.php` may have new connection options.

## Verifications
- [ ] For each project config file in the list above, run `diff -u config/{name}.php vendor/laravel/framework/config/{name}.php 2>/dev/null` and capture the output (some files may not exist on the framework side — that is expected).
- [ ] Summarize the deltas in Implementation Notes, grouped by file.
- [ ] For each delta, label it as **adopt** (apply to project config), **defer** (leave for a follow-up plan), or **skip** (project intentionally diverges).
- [ ] Apply only the **adopt** deltas. Leave **defer** and **skip** in place and explain in Implementation Notes.
- [ ] Do **not** delete project-specific keys (e.g., custom mail-from defaults) under any circumstance.

## Acceptance Criteria
- A delta report exists in Implementation Notes for each of the 11 config files.
- All **adopt**-labeled changes are applied; the diff after edits matches the operator's stated intent.
- `php artisan config:show` runs cleanly for `app`, `cache`, `database`, `mail`, `queue`, `session`.

## Implementation Notes
*(left blank — filled by the executor)*
