# Task 010: Hand off manual smoke test, then commit upgrade

**Status**: pending
**Depends on**: 009
**Retry count**: 0

## Description
The upgrade itself is now applied and the framework boots. Hand the application back to the operator (Dipak) for a manual browser-based smoke test of all feature areas (Auth, Contacts, FAQs, Products, Blog, Notifications, Comment voting). On a green smoke test, commit `composer.json`, `composer.lock`, and any code edits as a single atomic commit on `chore/upgrade-laravel-13`. This is the only commit step in the plan.

## Context
- Frontend assets need a rebuild after framework upgrade in case any Vite-managed CSS/JS reference moved (`npm run build`).
- Smoke test cannot be automated by Claude — operator must drive the browser.
- Login: `dipakp@logicrays.com` / `dipak@123` at `http://laraveldemo.local/login` (or `http://127.0.0.1:8000/login` if using `php artisan serve`).
- Existing rows are preserved (verified in 009).
- **Mail dispatch**: contact creation triggers a queued `SendContactEmail` job. To prevent the smoke test from failing on SMTP errors, instruct the operator to set `MAIL_MAILER=log` in `.env` for the duration of the smoke test (revert after if needed). Logged mail goes to `storage/logs/laravel.log`.

## Verifications

### Operator pre-flight
- [ ] Operator confirms `MAIL_MAILER=log` in `.env` (or another non-SMTP mailer) before exercising any contact-creation flow. If not, set it temporarily.
- [ ] Operator runs `npm run build`.
- [ ] Operator hits the application via `php artisan serve` OR the existing Apache vhost (`http://laraveldemo.local`).

### Operator-driven smoke test (manual, ordered)
For each step, the expected outcome is **(a) page renders, (b) no PHP error/exception page, (c) no 419 "Page Expired" CSRF error, (d) no 500 server error**.

1. [ ] **Logout flow**: if logged in, log out. Confirm redirect to `/`.
2. [ ] **Login form GET**: open `/login`. Confirm form renders. View source — confirm a `_token` hidden input is present and non-empty (this exercises CSRF token generation).
3. [ ] **Login form POST**: submit `dipakp@logicrays.com` / `dipak@123`. Confirm redirect to `/dashboard`. **Watch specifically for "419 Page Expired"** — that would indicate the CSRF middleware rename is broken.
4. [ ] **Contacts list**: open `/contacts`. Confirm 13 existing rows (baseline 13 from task 001) render.
5. [ ] **Contact create form GET**: open `/contacts/create`. Confirm form renders.
6. [ ] **Contact create POST**: create a new test contact. Confirm redirect (no 500/419). Confirm row count is now 14 (visible in list). The mail dispatch should write to `storage/logs/laravel.log` rather than throw.
7. [ ] **Contact edit + delete**: edit the new test contact. Then delete it. Confirm row count returns to 13.
8. [ ] **FAQs list**: open `/faqs`. Confirm 56 rows render (corrected baseline; was 55 in original plan).
9. [ ] **FAQs API**: in a new tab or via curl, open `http://127.0.0.1:8000/api/faqs` (or `http://laraveldemo.local/api/faqs`). Confirm a JSON array of FAQs is returned (not HTML, not 500). The API is not Sanctum-protected for `index` so this works without auth.
10. [ ] **Products list**: open `/products`. Confirm 140 rows render (corrected baseline; was 135 in original plan).
11. [ ] **Products with images**: click into one product detail page (if available) or scroll the list. Confirm at least one product image renders (this verifies the storage symlink is intact).
12. [ ] **Blog index**: open `/posts` (or `/blog` if that's the public route). Confirm 21 posts render (corrected baseline; was 20 in original plan).
13. [ ] **Blog post detail + comments**: open a single post. Confirm comments render (total comments=280 across all posts; corrected baseline).
14. [ ] **Notifications page**: open `/notifications`. Confirm page renders (may be empty if no notifications).
15. [ ] **Comment voting** (if voting UI is present): try voting on a comment. Confirm no error.
16. [ ] **Logout**: log out. Confirm redirect to `/`.
17. [ ] No PHP exception page (Ignition / Whoops) appeared at any point.
18. [ ] Operator says "smoke test green" before the commit step.

### Claude-driven (after operator green-lights)
- [ ] `git status --short` lists exactly the expected files: `composer.json`, `composer.lock`, plus a small set of `app/`, `config/`, or `bootstrap/app.php` edits from 003–007.
- [ ] `git diff --stat` is reviewed and surfaced to the operator one more time.
- [ ] `git add` of **only** the upgrade-related files (do not stage `.claude/`, `CLAUDE.md`, `db/laraveldemo.pre-upgrade-2026-04-29.sql`, `.env`, or `.gitignore` changes from project-setup work — those are a separate concern).
- [ ] If the operator changed `.env` for the smoke test (MAIL_MAILER), confirm `.env` is **not** staged (it should be gitignored anyway).
- [ ] `git commit -m "chore: upgrade to Laravel 13"` succeeds.
- [ ] `git log --oneline -1` shows the commit on `chore/upgrade-laravel-13`.

## Acceptance Criteria
- Operator confirms manual smoke test passed all 18 steps.
- Single commit lands on `chore/upgrade-laravel-13` containing only upgrade-related changes.
- The plan's `_plan.md` status is updated to `completed`.
- `db/laraveldemo.pre-upgrade-2026-04-29.sql` remains in place (it is **not** committed in this plan; treat it as a local safety artifact unless the operator separately decides to commit it).
- Operator reverts any temporary `.env` edit (e.g., `MAIL_MAILER`) after the commit lands, if applicable.

## Rollback

### If smoke test fails before commit
1. Stop the dev server (`pkill -f 'artisan serve'` or Ctrl-C).
2. Capture the failing step and any error output (screenshot, stack trace from `storage/logs/laravel.log`) for the post-mortem.
3. **Clean rollback (recommended)**:
   - `git checkout master -- composer.json composer.lock`
   - `rm -rf vendor/` (purges any L13 + half-applied state)
   - `composer install` (restores Laravel 12 vendor tree from the master lockfile)
   - `git restore .` (reverts any code edits from 003–007)
   - `php artisan optimize:clear`
4. **Verify rollback**: `php artisan --version` reports Laravel 12.x.x.
5. **DB rollback (only if data was somehow modified)**:
   - `php artisan tinker --execute="echo DB::table('faqs')->count();"` should still return 55.
   - If counts differ from baseline: `mysql -u root -psecret laraveldemo < db/laraveldemo.pre-upgrade-2026-04-29.sql`.
   - In this plan, no task should write to the DB, so this rollback step is purely insurance.

### If a regression is discovered AFTER the commit lands
- `git revert HEAD` — single-commit revert. Push if appropriate.
- Do **NOT** force-push or amend; preserve the audit trail.
- Then follow the clean-rollback steps 3–5 above.

### If `composer update` itself failed mid-task-002 (hybrid `vendor/`)
- This is the most likely failure mode given the legacy skeleton + `--no-scripts` workaround.
- `rm -rf vendor/`
- `git checkout master -- composer.json composer.lock` (revert composer files)
- `composer install` (clean L12 install from the master lockfile)
- Re-run task 002 with whatever fix was identified.

## Implementation Notes
*(left blank — filled by the executor)*
