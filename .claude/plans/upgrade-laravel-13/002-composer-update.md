# Task 002: Run `composer update` to lock Laravel 13 dependencies

**Status**: pending
**Depends on**: 001
**Retry count**: 0

## Description
Regenerate `composer.lock` and install the Laravel 13 dependency tree under `vendor/`. This is the destructive step that replaces the L12 framework code with L13 in `vendor/`. The lockfile is the only project-tracked file written; rollback is `git checkout master -- composer.lock && rm -rf vendor/ && composer install`.

**Critical sequencing note**: this project uses the **legacy L10 skeleton** (`app/Http/Kernel.php`, `app/Console/Kernel.php`, `app/Exceptions/Handler.php`, `config/app.php` providers array). Composer's `post-autoload-dump` hook runs `php artisan package:discover` which boots the app. If the legacy skeleton has any L13 boot incompatibility (e.g., the renamed `VerifyCsrfToken` framework class), the post-autoload step will fail and leave `vendor/` in a hybrid state. To avoid this, this task uses `--no-scripts` to defer all artisan-level scripts until after tasks 003–007 have applied the breaking-change fixes. Scripts are re-run at the end of task 008.

## Context
- `composer.json` is already on the L13 constraints (verified in 001).
- Local PHP is 8.4.20 (≥ 8.3 minimum required by L13).
- Composer is 2.8.8.
- Composer's `post-update-cmd` runs `php artisan vendor:publish --tag=laravel-assets --ansi --force`. This will be deferred via `--no-scripts` and re-run in task 008.
- Composer's `post-autoload-dump` runs `php artisan package:discover --ansi`. Also deferred via `--no-scripts`.
- We expect ~80 packages to update; the resolve step will take 30–90 seconds.
- `laravel/breeze: ^2.4` may need a higher floor for L13 compatibility — verify with a why-not check before running the real update.

## Verifications
- [ ] `composer why-not laravel/framework 13.0` reports nothing blocking (or only the in-progress L12 lock, which is expected).
- [ ] `composer update --dry-run --with-all-dependencies` resolves without errors and shows `laravel/framework` moving to `13.x`. If `laravel/breeze` resolution fails, raise the floor in `composer.json` (e.g., `^2.5`) and re-run dry-run before proceeding.
- [ ] Real `composer update --with-all-dependencies --no-scripts --no-interaction` succeeds (exit 0). **`--no-scripts` is mandatory** to suppress `package:discover` and `vendor:publish` until tasks 003–007 land.
- [ ] **Immediately after** the update succeeds, run `php artisan optimize:clear` to purge any stale `bootstrap/cache/config.php`, `bootstrap/cache/services.php`, `bootstrap/cache/packages.php`, compiled views, and file-cache entries left over from L12. This is read-only with respect to the database.
- [ ] `vendor/laravel/framework/composer.json` declares version `13.x` (`grep '"version"' vendor/laravel/framework/composer.json` shows `13.`, or `composer show laravel/framework | grep versions` shows `13.x`).
- [ ] `composer show laravel/tinker` reports `3.x`, `phpunit/phpunit` `12.x`, `nunomaduro/collision` `9.x`, `spatie/laravel-ignition` `3.x`.
- [ ] `composer.lock` content-hash matches `composer.json` (`composer validate --no-check-publish` exits 0).
- [ ] No file outside `composer.lock`, `vendor/`, `bootstrap/cache/`, and `public/vendor/` was modified by the update (`git status --short` shows nothing else).
- [ ] `git status --short public/` is empty (confirms no surprise public asset writes — note: scripts were skipped, so this is a baseline; re-checked after task 008).
- [ ] `php -l app/Http/Kernel.php`, `php -l app/Http/Middleware/VerifyCsrfToken.php`, and `php -l bootstrap/app.php` all exit 0 (lint only — does not boot the app, so will not throw on missing classes).
- [ ] DO NOT run `php artisan --version` or `php artisan about` yet — the legacy skeleton may not boot until task 003 fixes CSRF references. Defer those checks to task 009.

## Acceptance Criteria
- `composer update` succeeded with `--no-scripts`.
- `optimize:clear` ran successfully (it does not require a clean app boot to clear caches; if it fails, document the failure and proceed — the cache files can be deleted directly via `rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/data/*`).
- Lint of the three skeleton files passes.
- If `composer update` itself fails (resolver error, network), abort and surface to the user.
- Do not run `php artisan migrate`, `php artisan about`, `php artisan route:list`, or any DB-touching command yet.

## Implementation Notes
*(left blank — filled by the executor)*
