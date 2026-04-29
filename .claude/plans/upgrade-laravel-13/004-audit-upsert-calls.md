# Task 004: Audit `upsert()` calls for empty `uniqueBy`

**Status**: pending
**Depends on**: 002
**Retry count**: 0

## Description
Laravel 13 makes `Model::upsert()` and `DB::table()->upsert()` throw `InvalidArgumentException` when the `uniqueBy` argument is an empty array. Previously this silently degraded behavior. Find every call site and confirm `uniqueBy` is non-empty.

## Context
- Files to scan: everything under `app/`, plus `database/seeders/`, `routes/`, and any `app/Console/Commands/`.
- Method signatures: `upsert(array $values, array|string $uniqueBy, array|null $update = null)`.
- The bug only triggers on MySQL/MariaDB (per upgrade guide).

## Verifications
- [ ] `grep -rn '->upsert(' app/ database/ routes/ 2>/dev/null` lists every call site (could be zero).
- [ ] For each call site, the second argument is a non-empty `array` literal, a non-empty `string`, or a variable proven non-empty by the surrounding code.
- [ ] If a call site is found that may pass an empty `uniqueBy`, fix it with the canonical column(s) and add a one-line comment **only if the constraint is non-obvious** (per project standards: no narration comments).
- [ ] After any fix: `./vendor/bin/pint --test` exits 0 on the touched file(s) (style clean).

## Acceptance Criteria
- All `upsert()` call sites are documented in Implementation Notes.
- No call site can pass an empty array.
- If `grep` returns zero hits, this task is a no-op — record that and move on.

## Implementation Notes
*(left blank — filled by the executor)*
