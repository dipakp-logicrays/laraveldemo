# Task 001: Verify pre-upgrade state and snapshot

**Status**: pending
**Depends on**: none
**Retry count**: 0

## Description
Confirm we are starting from a known-good baseline before any destructive step. Verify the upgrade branch is checked out, the DB backup exists and is sane, and the working tree's `composer.json` is staged with the L13 bumps but the lockfile is still on Laravel 12. This is a pure read-only inspection — nothing is written.

## Context
- Branch: `chore/upgrade-laravel-13` (already created from `master`).
- Backup: `db/laraveldemo.pre-upgrade-2026-04-29.sql` (~200 KB, 16 tables).
- `composer.json` already edited in the working tree (uncommitted).
- `composer.lock` and `vendor/` still on Laravel 12.

## Verifications
- [ ] Current branch is `chore/upgrade-laravel-13` (`git rev-parse --abbrev-ref HEAD`).
- [ ] Backup file exists and is non-empty (`test -s db/laraveldemo.pre-upgrade-2026-04-29.sql`).
- [ ] Backup contains all 16 expected tables (`grep -c '^CREATE TABLE' db/laraveldemo.pre-upgrade-2026-04-29.sql` → 16).
- [ ] **Capture baseline MD5 of the backup**: `md5sum db/laraveldemo.pre-upgrade-2026-04-29.sql` — record the hash in Implementation Notes for post-upgrade comparison if a re-dump is ever needed.
- [ ] **Capture pre-upgrade row counts** (read-only) and record in Implementation Notes — these are the baseline for task 009's comparison. Use a one-shot read-only PHP snippet (do NOT use `php artisan tinker` if the framework cannot boot pre-upgrade for any reason; a direct PDO check is acceptable):
  - `faqs` count (baseline 56)
  - `products` count (baseline 140)
  - `contacts` count (baseline 13)
  - `posts` count (baseline 21)
  - `comments` count (baseline 280)
  - `users` count (baseline 12)
  - **Note**: original plan listed 55/135/20/279 from `information_schema.TABLES.TABLE_ROWS` (InnoDB approximation). Exact `COUNT(*)` confirmed at task 001 execution as 56/140/13/21/280/12, matching the backup. Use the corrected baseline above for task 009 comparison.
  - If any count differs from this corrected baseline at task 009 (post-upgrade), **abort and roll back** — that would indicate live data was modified during the upgrade, which violates the data-safety constraint.
- [ ] `composer.json` declares `"laravel/framework": "^13.0"` and `"php": "^8.3"` (`grep -E '"(laravel/framework|php)":' composer.json`).
- [ ] `composer.lock` still shows the *old* Laravel 12 framework version (`grep -A1 '"name": "laravel/framework"' composer.lock | head -2` shows `12.x`).
- [ ] Working PHP runtime is ≥ 8.3 (`php -r 'exit(version_compare(PHP_VERSION, "8.3.0", "<") ? 1 : 0);'` exits 0).
- [ ] **Confirm legacy skeleton is in use** (informational — guides task 003's strategy):
  - `test -f app/Http/Kernel.php` exits 0.
  - `test -f app/Console/Kernel.php` exits 0.
  - `test -f app/Exceptions/Handler.php` exits 0.
  - Document this in Implementation Notes: "Project is on legacy L10 skeleton; bootstrap/app.php is L10-style; CSRF middleware lives in app/Http/Kernel.php and config/sanctum.php (not bootstrap/app.php)."
- [ ] No `phpunit.xml` / test command will be invoked anywhere in this plan (read the plan doc and confirm).

## Acceptance Criteria
- All verifications pass.
- No file is written, no command is executed beyond read-only inspection.
- If any verification fails, **abort the plan** and surface the discrepancy to the user; do not auto-fix.

## Implementation Notes
*(left blank — filled by the executor)*
