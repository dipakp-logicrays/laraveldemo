# Task 008: Run Laravel Pint to auto-fix style

**Status**: pending
**Depends on**: 003, 004, 005, 006, 007
**Retry count**: 0

## Description
After the dependency bump and the code-side breaking-change edits, run Laravel Pint with the default `laravel` preset to normalize style across all touched files. Pint may also flag changes introduced by Laravel's own coding-standard updates between major versions.

**Also**: task 002 ran `composer update` with `--no-scripts` to avoid booting the app before tasks 003–007 fixed breaking changes. Now that the code is L13-compatible, this task re-runs the deferred composer scripts: `composer dump-autoload` (regenerates the autoload + triggers `package:discover`) and `composer run-script post-update-cmd` (runs `vendor:publish --tag=laravel-assets`).

## Context
- Pint is already declared in `composer.json` (`laravel/pint: ^1.0`) and resolves to whatever version is compatible with L13.
- Run mode: in-place auto-fix, then a verification re-run with `--test` to confirm clean.
- Pint's reformatting may shuffle the imports edited by task 003 (`use ... as Middleware;`). Verify that the FQCN itself was not altered — only whitespace/ordering.

## Verifications
- [ ] `./vendor/bin/pint` exits 0 (auto-fix mode) and lists every file touched.
- [ ] `./vendor/bin/pint --test` exits 0 (no remaining violations).
- [ ] `git diff --name-only` shows the union of files edited by tasks 003–007 plus any Pint-only touches.
- [ ] No `vendor/`, `node_modules/`, or `storage/` file is in the diff.
- [ ] `git diff app/Http/Middleware/VerifyCsrfToken.php` (or whichever files task 003 touched) shows the parent-class import change is preserved post-Pint (i.e., Pint did not revert the rename).
- [ ] **Re-run deferred composer scripts**:
  - `composer dump-autoload --optimize` exits 0 and emits `Discovered Package: ...` lines without errors. (This invokes `package:discover` via `post-autoload-dump`.)
  - `composer run-script post-update-cmd` exits 0. (This runs `vendor:publish --tag=laravel-assets --ansi --force`.)
- [ ] After the script re-runs: `git status --short public/` shows zero new files (or only files we accept — review any new entries with the operator before proceeding).
- [ ] `git status --short bootstrap/cache/` may show new `services.php` / `packages.php` files — these are gitignored and expected.

## Acceptance Criteria
- Working tree is style-clean per Pint's default Laravel preset.
- Pint did not introduce semantic changes — only whitespace, quoting, ordering, and import formatting.
- Composer's deferred scripts ran successfully against the L13-compatible code.
- No surprise public asset writes from `vendor:publish`.

## Implementation Notes
*(left blank — filled by the executor)*
