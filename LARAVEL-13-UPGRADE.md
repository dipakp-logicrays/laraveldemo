# Laravel 12 → 13 Upgrade — Step-by-Step Guide

This document records the Laravel 13 upgrade for **laraveldemo** and is the canonical reference for both server deployment and the local upgrade work.

| | |
|---|---|
| **Upgraded on** | 2026-04-29 |
| **From** | Laravel `^12` (v12.13.0) |
| **To** | Laravel `^13.0` (v13.7.0) |
| **PHP min** | bumped `^8.2` → `^8.3` |
| **Branch** | `chore/upgrade-laravel-13` |
| **Pull Request** | <https://github.com/dipakp-logicrays/laraveldemo/pull/19> |
| **Plan + audit trail** | `.claude/plans/upgrade-laravel-13/` |
| **DB safety snapshot** | `db/laraveldemo.pre-upgrade-2026-04-29.sql` |

> 📌 **Pull Request:** <https://github.com/dipakp-logicrays/laraveldemo/pull/19>
> The full diff, commit-by-commit history, and review discussion live there.

---

## 1. Pre-requisites

Before starting, confirm the host has:

- **PHP 8.3 or higher** — `php -v`. Laravel 13 will refuse to install on PHP 8.2.
- **Composer 2.x** — `composer --version`.
- **MySQL / MariaDB** — `mysql --version`.
- **Git** — `git --version`.
- **Node.js + npm** — for Vite asset build.
- A **clean working tree** — `git status` should be clean before starting (or work on a dedicated branch).
- A **fresh database backup** — never start a framework upgrade without one.

---

## 2. Server Deployment Steps

This is the section you'll use most often: deploying the merged PR to your server. The local upgrade work is already done and merged — see §3 if you ever need to repeat the upgrade process for a future Laravel version.

### 2.1 Pre-flight checks on the server

Run these BEFORE pulling any code. If PHP is too old, the deploy will fail and you'll be left with a broken server.

```bash
# Verify PHP runtime is 8.3 or higher.
# Laravel 13 hard-requires PHP ^8.3 — it will refuse to install on PHP 8.2.
php -v

# Verify MySQL / MariaDB is available and reachable.
mysql --version

# Verify Node.js is present (needed for npm run build in step 2.2.5).
node --version
```

If `php -v` shows 8.2.x or older, **stop here** and upgrade PHP on the server first. Do not proceed.

### 2.2 Deployment sequence

Run these on the server, in this order. Each command's purpose is commented above it.

```bash
# Move into the project directory on your server.
cd /var/www/html/laraveldemo
```

#### Step 1 — Pull the merged PR

```bash
# Fetch the latest commits from GitHub master branch and fast-forward
# your local server checkout to match. This brings in the L13 upgrade
# commits (composer.json, composer.lock, the CSRF middleware edit, etc.).
git pull origin master
```

#### Step 2 — Clear caches BEFORE installing new packages

```bash
# Clear bootstrap/cache/{config,services,packages,routes}.php and
# storage/framework/views/* and the file-based cache.
# Why BEFORE composer install: a cached config from L12 (created by an
# earlier `php artisan config:cache`) could reference classes that no
# longer exist in L13's vendor/, causing the framework to crash before
# composer install can even run. Clearing first is the safer order.
php artisan optimize:clear
```

#### Step 3 — Install the LOCKED dependency tree

```bash
# composer install reads composer.lock and installs EXACTLY those versions.
# This is the single most important command on the server.
#
# --no-dev   skips dev-only packages (PHPUnit, Pint, Ignition, breeze
#            scaffolding) — these are not needed in production and reduce
#            disk + autoload size.
# --optimize-autoloader  generates a class map for faster autoloading.
#
# Do NOT use `composer update` on the server — that ignores the lockfile
# and could pull versions newer than what was tested.
composer install --no-dev --optimize-autoloader
```

#### Step 4 — Ensure storage symlink exists

```bash
# Recreates the public/storage -> storage/app/public symlink if missing.
# This is what makes uploaded images accessible via the public URL.
# The command is idempotent — if the symlink already exists it does nothing.
php artisan storage:link
```

#### Step 5 — Rebuild frontend assets

```bash
# Install the EXACT npm dependencies from package-lock.json.
# `npm ci` is to npm what `composer install` is to composer:
# reproducible, lockfile-driven, faster than `npm install` for CI/deploy.
npm ci

# Build production-mode CSS/JS bundles via Vite.
# Outputs to public/build/. Tailwind classes get tree-shaken,
# JS gets minified.
npm run build
```

#### Step 6 — Run migrations (safe)

```bash
# Runs ONLY pending migrations (those NOT yet recorded in the `migrations`
# table). Existing tables and data are NEVER touched.
#
# --force bypasses the "Application In Production. Are you sure?" prompt
# that Laravel shows in production environments. It does NOT make the
# command more aggressive — it just skips a confirmation that no human
# is around to type "yes" to on a deploy script.
#
# For the L13 upgrade specifically this is a no-op (we added no new
# migrations). It will print "Nothing to migrate." But it's the standard
# step on every deploy because future PRs may add migrations.
php artisan migrate --force
```

> ⚠️ **NEVER run these commands on production** — they wipe data:
> `migrate:fresh`, `migrate:fresh --seed`, `migrate:refresh`, `migrate:reset`, `db:wipe`, `db:seed` (except in tightly-controlled situations).

#### Step 7 — Re-cache for performance (production only)

```bash
# Compile config/*.php into a single cached array at bootstrap/cache/config.php
# so Laravel doesn't re-parse every config file on every request.
# IMPORTANT: after this runs, env() returns null OUTSIDE config files.
# That's why the convention is "never read env() outside config/".
php artisan config:cache

# Compile route definitions into a serialized cache. Faster route resolution.
# Caveat: closures cannot be cached. If any route uses Route::get('/x', function() {}),
# this command fails. The project uses controller routes everywhere, so it works.
php artisan route:cache

# Pre-compile every Blade template to plain PHP under storage/framework/views/.
# Eliminates the runtime Blade compile pass for the first request after deploy.
php artisan view:cache
```

If you prefer one command, `php artisan optimize` runs `config:cache`, `route:cache`, and `event:cache` together.

> Skip step 7 entirely on dev / staging machines — uncached config makes debugging easier (and `dd($var)` in a config file actually shows up).

#### Step 8 — Restart queue workers

```bash
# Sends a graceful-restart signal to any long-running queue worker
# (php artisan queue:work). Workers boot the framework once and reuse
# that instance for every job — without this signal they'd keep using
# the OLD vendor/ code in memory and ignore your new L13 code.
#
# This project uses QUEUE_CONNECTION=database and the SendContactEmail
# job, so workers must restart after every deploy.
php artisan queue:restart
```

If you use Supervisor / systemd to manage the queue worker, the next worker pickup will be on the new code automatically.

#### Step 9 — Reload PHP-FPM

```bash
# PHP-FPM (the service that runs PHP behind Apache/nginx) caches
# compiled PHP files in opcache. After composer install replaces
# vendor/ files, opcache still serves the OLD compiled bytecode
# until you reload PHP-FPM.
#
# Substitute php8.3-fpm with whichever version your server runs:
#   php-fpm, php8.3-fpm, php8.4-fpm — check with `systemctl list-units '*fpm*'`
sudo systemctl reload php8.3-fpm
```

If you use Apache with mod_php (not PHP-FPM), reload Apache instead:

```bash
sudo systemctl reload apache2
```

### 2.3 Why `composer install`, not `composer update`

| Command | What it does | When to use |
|---|---|---|
| `composer install` | Installs **exactly** what's in `composer.lock` | Every deploy. Reproducible. |
| `composer update` | Ignores lockfile, resolves fresh, **rewrites** lockfile | Only when intentionally bumping deps (already done locally; committed) |
| `composer update --with-all-dependencies` | Same, plus transitive bumps | Same as above, more aggressive |

If you run `composer update` on the server you risk pulling versions **newer than what was tested**, and you rewrite `composer.lock` on the server which causes drift from `master` on the next deploy.

**Server: always `composer install`. Developer: `composer update` only when intentionally bumping, then commit the new lockfile.**

### 2.4 Post-deploy verification

After step 9, smoke-test the deployed code:

```bash
# Confirm framework version and PHP version are what you expect.
# Should print: Laravel Version: 13.7.0  /  PHP Version: 8.3.x or 8.4.x
php artisan about | grep -E "Laravel Version|PHP Version"

# Confirm all routes registered without errors. Expect at least 59 routes.
php artisan route:list | wc -l

# Tail the application log while you click through the site in a browser.
# Any L12-vs-L13 incompatibility surfaces here as a stack trace.
tail -f storage/logs/laravel.log
```

In a browser, hit:

- `/` — home renders
- `/login` — form renders, login works (no 419 errors → CSRF middleware works)
- `/contacts`, `/faqs`, `/products`, `/posts` — list pages render
- `/api/faqs` — JSON returns

If any of these break, jump to §4 Rollback.

---

## 3. Local Upgrade Steps (historical record / template for next upgrade)

These are the steps that produced the merged PR. Recorded here so the next major upgrade (L13 → L14, etc.) can follow the same pattern. **You don't need to run these for the L13 deploy** — that work is already done and merged.

### 3.1 Branch off master

```bash
# Switch to master and pull the latest before branching, so the new
# upgrade branch starts from a clean baseline.
git checkout master
git pull origin master

# Create a dedicated branch for the upgrade. Keeps the work isolated
# until the smoke test passes; if anything goes wrong, just delete
# the branch and master is unaffected.
git checkout -b chore/upgrade-laravel-13
```

### 3.2 Take a database backup

```bash
# Dump the entire laraveldemo database to a timestamped SQL file under db/.
# --single-transaction  takes a consistent snapshot without locking tables
#                       (works for InnoDB tables — which we use).
# --routines            includes stored procedures / functions if any.
# --triggers            includes triggers.
# --add-drop-database   prefixes the dump with DROP DATABASE so the restore
#                       is fully self-contained.
mysqldump -u root -p \
  --single-transaction --routines --triggers --add-drop-database \
  --databases laraveldemo \
  > db/laraveldemo.pre-upgrade-$(date +%F).sql

# Sanity-check the dump:
# - 16 CREATE TABLE statements (one per table in the schema).
# - File size > 0 (a corrupt mysqldump can silently produce a 0-byte file).
grep -c '^CREATE TABLE' db/laraveldemo.pre-upgrade-*.sql
ls -la db/laraveldemo.pre-upgrade-*.sql
```

### 3.3 Bump `composer.json` constraints

Edit `composer.json` so that:

```json
{
    "require": {
        "php": "^8.3",
        "guzzlehttp/guzzle": "^7.2",
        "laravel/breeze": "^2.4",
        "laravel/framework": "^13.0",
        "laravel/sanctum": "^4.3",
        "laravel/tinker": "^3.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.0",
        "laravel/sail": "^1.18",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.1",
        "phpunit/phpunit": "^12.0",
        "spatie/laravel-ignition": "^2.12"
    }
}
```

> **Note:** `nunomaduro/collision` and `spatie/laravel-ignition` did NOT bump to v9/v3 — those don't exist yet. The `8.x` and `2.12+` lines already declare Laravel 13 support.

### 3.4 Dry-run the resolver

```bash
# --dry-run                    show what WOULD change but don't write to vendor/ or composer.lock.
# --with-all-dependencies      allow upgrading transitively-locked packages (Symfony, sebastian/*, etc.)
#                              that the new constraints require.
#
# This is your safety net. If the resolver fails here, your composer.json
# constraints are wrong. Fix them and re-run BEFORE doing the real update.
composer update --dry-run --with-all-dependencies
```

If you see errors like `nunomaduro/collision[v9.0]` not found — your constraints are wrong. Fix them and dry-run again until clean.

### 3.5 Run the real update with `--no-scripts`

```bash
# Same as the dry-run, but actually writes vendor/ and composer.lock.
#
# --no-scripts is CRITICAL for major version upgrades:
# Composer's post-autoload-dump hook runs `php artisan package:discover`
# which boots the Laravel app. If any breaking change in your code causes
# boot failure, this leaves vendor/ in a hybrid half-applied state
# (some packages on L13, some on L12). With --no-scripts we install the
# new vendor/ first, fix the code (steps 3.7), then re-run the scripts
# in step 3.9.
#
# --no-interaction skips any "do you want to allow this plugin?" prompts
# (useful in scripted runs).
composer update --with-all-dependencies --no-scripts --no-interaction
```

### 3.6 Clear stale caches

```bash
# Same reasoning as in the server deploy: the L12 cached files under
# bootstrap/cache/ may reference classes that no longer exist in L13.
# Clear them before running any artisan command that boots the app.
php artisan optimize:clear
```

### 3.7 Apply Laravel 12 → 13 code changes

Reference: <https://laravel.com/docs/13.x/upgrade>

**Audit your project for breaking-change patterns:**

```bash
# 1. CSRF middleware rename (HIGH impact in L13).
#    Find every reference to VerifyCsrfToken so you can rename them.
grep -rn 'VerifyCsrfToken' bootstrap/ app/ config/ routes/ resources/

# 2. Unsafe upsert calls (MEDIUM impact).
#    L13 throws InvalidArgumentException if uniqueBy is empty.
grep -rn '->upsert(' app/ database/ routes/

# 3. Cache writes that store objects (MEDIUM impact).
#    L13's serializable_classes default is now false; objects need allow-listing.
grep -rnE '(Cache::|cache\()' app/ routes/ database/

# 4. Queue dispatch sites (MEDIUM impact).
#    Same rationale as cache writes — Eloquent models in queue payloads
#    need SerializesModels trait or an allow-list.
grep -rnE '(dispatch\(|Bus::dispatch|Queue::push|::dispatchSync|::dispatchAfterResponse)' app/ routes/ database/

# 5. Renamed event properties (LOW impact).
#    JobAttempted::$exceptionOccurred -> $exception
#    QueueBusy::$connection -> $connectionName
grep -rnE '(JobAttempted|QueueBusy)' app/

# 6. Direct contract implementers (LOW impact).
#    Classes that `implements Illuminate\Contracts\...` may need new methods.
grep -rnE 'implements .*Contracts\\' app/

# 7. Legacy-skeleton vendor classes still exist?
#    This codebase uses the old L10 skeleton (Kernel.php files + provider
#    base classes). Verify L13 still ships those base classes.
for f in \
  vendor/laravel/framework/src/Illuminate/Foundation/Support/Providers/EventServiceProvider.php \
  vendor/laravel/framework/src/Illuminate/Foundation/Support/Providers/AuthServiceProvider.php \
  vendor/laravel/framework/src/Illuminate/Foundation/Support/Providers/RouteServiceProvider.php \
  vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php \
  vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php; do
    test -f "$f" && echo "OK $f" || echo "MISSING $f"
done
```

**For this codebase the only edit needed was:**

```diff
# app/Http/Middleware/VerifyCsrfToken.php
-use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
+use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as Middleware;
```

The local class FQCN `App\Http\Middleware\VerifyCsrfToken` is retained, so `app/Http/Kernel.php` and `config/sanctum.php` need no edit. Laravel 13 ships a `@deprecated` `VerifyCsrfToken` alias, so the old import would still work — the rename is defensive.

All other audited areas had **zero hits**: no `upsert()` calls, no cache writes of objects, no `JobAttempted`/`QueueBusy` listeners, no direct contract implementers. The single queued job (`app/Jobs/SendContactEmail.php`) uses the `SerializesModels` trait — payload only carries `[class, id]`, so L13's tighter unserialization is satisfied.

### 3.8 Run Pint

```bash
# Auto-fix all PHP files to match Laravel's preset coding standard.
# Pint is pre-installed via composer; no need to install separately.
./vendor/bin/pint

# Verify the auto-fix didn't miss anything. Should exit 0.
# --test mode runs the same checks but only reports violations
# (does not write any file).
./vendor/bin/pint --test
```

### 3.9 Re-run the deferred composer scripts

```bash
# Now that the code is L13-compatible, run the scripts we deferred in 3.5.
#
# composer dump-autoload regenerates the autoloader and triggers the
# post-autoload-dump hook (which runs `php artisan package:discover`,
# detecting the L13 service providers shipped by each package).
# --optimize generates a class map for production-grade autoload speed.
composer dump-autoload --optimize

# Runs the post-update-cmd defined in composer.json scripts section,
# which is `php artisan vendor:publish --tag=laravel-assets --ansi --force`.
# For this project that prints "No publishable resources" — it's a no-op.
composer run-script post-update-cmd
```

### 3.10 Verify framework boot (read-only checks)

```bash
# Clear caches one more time so any inspection commands run against
# fresh config and route data.
php artisan optimize:clear

# Print framework metadata. Should show:
#   Laravel Version: 13.7.0
#   PHP Version: 8.4.x (or 8.3.x)
#   Database: mysql
#   public/storage: LINKED
php artisan about

# Resolve every registered route. If a controller class is missing or
# a middleware alias is unregistered, this command reports it.
php artisan route:list

# Show the resolved app config (post-cache, post-merge).
php artisan config:show app

# Confirm the public/storage symlink survived the upgrade.
test -L public/storage && echo "storage symlink OK"
```

### 3.11 Request-level smoke (read-only routes)

```bash
# Start a local PHP dev server in the background on port 8765.
# Backgrounding (&) lets us run curl in the same shell.
php artisan serve --port=8765 &

# Wait 2 seconds for the server to be ready.
sleep 2

# Hit three known-read-only routes. Each should return 200.
# These exercise the lazily-resolved middleware stack (CSRF,
# Sanctum, route bindings) which `php artisan about` does NOT.
curl -sS -o /dev/null -w '%{http_code}\n' http://127.0.0.1:8765/         # 200 (home)
curl -sS -o /dev/null -w '%{http_code}\n' http://127.0.0.1:8765/login    # 200 (must contain _token CSRF input)
curl -sS -o /dev/null -w '%{http_code}\n' http://127.0.0.1:8765/api/faqs # 200 (JSON body)

# Stop the dev server.
pkill -f 'artisan serve'
```

### 3.12 Manual browser smoke test

Drive the browser yourself through the 18 ordered steps in `.claude/plans/upgrade-laravel-13/010-smoke-test-and-commit.md`. The critical checks:

- Login form GET — `_token` hidden input present
- Login POST — no 419 "Page Expired"
- Each feature area renders: contacts, FAQs, FAQ API, products (with images), blog posts + comments, notifications

### 3.13 Take a post-upgrade snapshot

```bash
# Same dump command as in 3.2, but timestamped as "post-upgrade".
# Useful for audit and as a known-good restore point — the post-upgrade
# data state is the new baseline going forward.
mysqldump -u root -p \
  --single-transaction --routines --triggers --add-drop-database \
  --databases laraveldemo \
  > db/laraveldemo.post-upgrade-$(date +%F).sql
```

### 3.14 Commit, push, open PR

```bash
# Stage everything related to the upgrade itself: composer files,
# code edits, Pint normalizations.
git add composer.json composer.lock app/ bootstrap/ config/ routes/ database/

# Single descriptive commit explaining the upgrade.
git commit -m "chore: upgrade to Laravel 13"

# Stage the DB snapshots as a separate commit (audit / rollback artifacts).
git add db/laraveldemo.{pre,post}-upgrade-*.sql
git commit -m "chore: add pre/post Laravel 13 upgrade DB snapshots"

# Push the branch to GitHub. -u tracks the branch upstream so future
# `git push` and `git pull` work without specifying remote/branch.
git push -u origin chore/upgrade-laravel-13

# Open the PR via GitHub CLI. Pre-fills title and body for review.
gh pr create --title "chore: upgrade to Laravel 13" --body "..."
```

After PR review and merge to `master`, return to §2 to deploy on the server.

---

## 4. Rollback

If the upgrade breaks production after deploy, two rollback paths.

### 4.1 Code-level rollback (preferred)

The PR is one merge commit on master, so revert it cleanly:

```bash
# On the server, in the project root:
cd /var/www/html/laraveldemo

# Fetch the latest refs from origin (does not touch your working tree).
git fetch

# Create a new "revert" commit that undoes the merge commit.
# Replace <merge-commit-sha> with the actual SHA from `git log`.
# This is non-destructive: the original commits stay in history,
# just have their effects undone.
git revert <merge-commit-sha>

# Pull the revert commit (if you pushed it from a developer machine first)
# OR continue with the locally-created revert.
git pull origin master

# Clear all framework caches (now stale because vendor/ is about to change).
php artisan optimize:clear

# Wipe vendor/ entirely. After a major version downgrade, leftover L13
# files in vendor/ can mix poorly with re-installed L12 files.
# Belt-and-braces: nuke and reinstall.
rm -rf vendor/

# Reinstall L12 dependency tree from the now-reverted composer.lock.
composer install --no-dev --optimize-autoloader

# Rebuild frontend assets.
npm ci && npm run build

# Reload PHP-FPM so opcache loads the L12 vendor/ files.
sudo systemctl reload php8.3-fpm
```

### 4.2 Code + DB rollback (if DB was somehow modified)

The pre-upgrade DB dump from `db/laraveldemo.pre-upgrade-2026-04-29.sql` is a hard restore point.

```bash
# Put the app into maintenance mode so users see "Be Right Back"
# instead of half-broken pages while you restore.
php artisan down

# Code rollback (same as §4.1).
git revert <merge-commit-sha>
rm -rf vendor/
composer install --no-dev --optimize-autoloader

# Restore the DB from the pre-upgrade dump. The dump uses
# --add-drop-database so this drops and recreates laraveldemo
# entirely with the pre-upgrade state.
mysql -u root -p laraveldemo < db/laraveldemo.pre-upgrade-2026-04-29.sql

# Verify a known row count to confirm the restore took.
# Pre-upgrade baseline was 56 FAQs.
php artisan tinker --execute="echo DB::table('faqs')->count();"

# Clear caches and exit maintenance mode.
php artisan optimize:clear
php artisan up

# Reload PHP-FPM.
sudo systemctl reload php8.3-fpm
```

**Note:** the upgrade itself did not modify any data, so §4.2 should rarely be needed. §4.1 is sufficient in 99% of failure cases.

---

## 5. What was deliberately deferred (follow-up work)

The L13 upgrade focused on the framework upgrade itself. The following items are deferred to separate plans:

1. **Test suite re-enablement.** The existing Breeze auth tests use `RefreshDatabase` against the unconfigured live MySQL connection — running them would wipe live data. Follow-up: wire up an isolated `laraveldemo_test` database, update `phpunit.xml` to PHPUnit 12 schema, re-enable the suite.
2. **`hashing.rehash_on_login` adoption.** Laravel 13 introduced this security feature. Adopting it changes login behavior, so it was deferred to avoid mixing with the upgrade.
3. **Migration to L11+ fluent skeleton.** This codebase uses the legacy L10 skeleton (`app/Http/Kernel.php`, `app/Console/Kernel.php`, `app/Exceptions/Handler.php`, providers array in `config/app.php`). Laravel 13 still supports it — verified during the upgrade. Migrating to the fluent `bootstrap/app.php` skeleton (`Application::configure()->...->create()`) is a separate refactor.
4. **Pre-existing unregistered `admin` middleware in `routes/web.php:83`.** Flagged during the audit; predates this upgrade.

---

## 6. Reference: full audit trail

Every grep, every diff decision, every verification result lives in:

```
.claude/plans/upgrade-laravel-13/
├── _plan.md                              ← overall plan + execution summary
├── _devils_advocate.md                   ← critical review record
├── 001-verify-pre-upgrade-state.md
├── 002-composer-update.md
├── 003-csrf-middleware-rename.md
├── 004-audit-upsert-calls.md
├── 005-cache-serializable-classes.md
├── 006-event-listener-contract-sweep.md
├── 007-config-diff-vs-l13-stubs.md
├── 008-pint-style-fix.md
├── 009-verify-framework-boot.md
└── 010-smoke-test-and-commit.md
```

Use the same plan structure as a template for the next major Laravel upgrade.

---

## 7. Quick command reference

### Server deploy (after PR is merged to master)

```bash
git pull origin master                                              # pull merged code
php artisan optimize:clear                                          # clear stale L12 caches first
composer install --no-dev --optimize-autoloader                     # install LOCKED L13 deps (NEVER `composer update` here)
php artisan storage:link                                            # ensure public/storage symlink
npm ci && npm run build                                             # rebuild Vite assets
php artisan migrate --force                                         # run pending migrations (no-op for L13 PR)
php artisan config:cache                                            # cache config (production only)
php artisan route:cache                                             # cache routes (production only)
php artisan view:cache                                              # precompile Blade templates (production only)
php artisan queue:restart                                           # signal queue workers to reload code
sudo systemctl reload php8.3-fpm                                    # reload PHP-FPM so opcache picks up new vendor/
```

### Local upgrade (developer — only when bumping deps)

```bash
git checkout -b chore/upgrade-laravel-XX                            # branch off master
mysqldump ... > db/laraveldemo.pre-upgrade-$(date +%F).sql          # back up DB
# ... edit composer.json constraints ...
composer update --dry-run --with-all-dependencies                   # safety check
composer update --with-all-dependencies --no-scripts --no-interaction  # real update
php artisan optimize:clear                                          # clear caches
# ... apply breaking-change fixes ...
./vendor/bin/pint                                                   # auto-fix style
composer dump-autoload --optimize                                   # re-run deferred autoload-dump
composer run-script post-update-cmd                                 # re-run deferred publish scripts
php artisan about && php artisan route:list                         # verify boot
# ... manual smoke test ...
git commit && git push && gh pr create                              # ship for review
```

### Rollback (server — if deploy goes wrong)

```bash
git revert <merge-sha>                                              # create revert commit on master
rm -rf vendor/                                                      # purge possibly-hybrid vendor tree
composer install --no-dev --optimize-autoloader                     # reinstall the previous LOCKED versions
npm ci && npm run build                                             # rebuild assets for previous version
php artisan optimize:clear                                          # clear caches
sudo systemctl reload php8.3-fpm                                    # reload PHP-FPM
# (if data was modified): mysql laraveldemo < db/laraveldemo.pre-upgrade-2026-04-29.sql
```
