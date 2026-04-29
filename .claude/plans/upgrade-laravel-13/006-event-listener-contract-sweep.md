# Task 006: Sweep event listeners + custom contracts for L13 signature changes

**Status**: pending
**Depends on**: 002
**Retry count**: 0

## Description
Laravel 13 renames two event properties and adds methods to several framework contracts. None of these break a stock Laravel skeleton, but if the app has custom listeners or implements framework contracts (rather than extending base classes) we have to update signatures.

**Additionally** (this project specific): the legacy L10 skeleton extends three framework base classes that may have been deprecated or removed in L13:
- `App\Providers\EventServiceProvider` extends `Illuminate\Foundation\Support\Providers\EventServiceProvider`.
- `App\Providers\AuthServiceProvider` extends `Illuminate\Foundation\Support\Providers\AuthServiceProvider`.
- `App\Providers\RouteServiceProvider` extends `Illuminate\Foundation\Support\Providers\RouteServiceProvider`.

If any of these vendor classes was removed in L13, the application will fail to boot. This task verifies their continued existence.

## Context
- Renames:
  - `Illuminate\Queue\Events\JobAttempted::$exceptionOccurred` → `::$exception`
  - `Illuminate\Queue\Events\QueueBusy::$connection` → `::$connectionName`
- New required contract methods (sample): `touch`, `dispatchAfterResponse`, `eventStream` — only matters if a class **implements** the contract directly.
- Files to scan: `app/Listeners/`, `app/Events/`, `app/Providers/EventServiceProvider.php`, anywhere a class implements a Laravel `Contracts\*` interface.
- Known callsites in this project (verified pre-plan):
  - `app/Listeners/FailedJobListener.php` reads `$event->job->resolveName()` and `$event->exception->getMessage()` — `JobFailed` event's `$exception` property is unchanged in L13, but verify.
  - `app/Listeners/SendContactNotification.php` uses a custom event `App\Events\ContactCreated`.

## Verifications
- [ ] `grep -rnE '(JobAttempted|QueueBusy)' app/ 2>/dev/null` lists any listener references.
- [ ] If `JobAttempted` references exist, every read of `->exceptionOccurred` is changed to `->exception`.
- [ ] If `QueueBusy` references exist, every read of `->connection` (when the source is the event object) is changed to `->connectionName`.
- [ ] `grep -rnE 'implements .*Contracts\\\\' app/ 2>/dev/null` lists any class implementing a Laravel contract interface directly (not extending a base class).
- [ ] For each direct contract implementer, cross-reference the L13 interface to confirm no newly-required methods are missing. If any are missing, add them with a sensible default (or stub that throws `LogicException` if behavior is genuinely undefined).
- [ ] **Legacy-skeleton class survival check** (read-only file existence — does not boot the app):
  - `test -f vendor/laravel/framework/src/Illuminate/Foundation/Support/Providers/EventServiceProvider.php`
  - `test -f vendor/laravel/framework/src/Illuminate/Foundation/Support/Providers/AuthServiceProvider.php`
  - `test -f vendor/laravel/framework/src/Illuminate/Foundation/Support/Providers/RouteServiceProvider.php`
  - If any is missing, **stop the plan** and surface to the operator. Migrating these is non-trivial; it likely means moving to the L11+ fluent skeleton and is out of scope for this plan.
- [ ] **Console kernel survival check**:
  - `test -f vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php`
  - `test -f vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php`
  - If either is missing, stop and surface — the legacy skeleton's `app/Console/Kernel.php` and `app/Http/Kernel.php` rely on these.
- [ ] After edits: `php artisan about` boots cleanly.

## Acceptance Criteria
- All event references and contract implementations are documented in Implementation Notes.
- Either the project has zero hits (task is a no-op) or every match is updated to the L13 signature.
- All legacy-skeleton vendor classes are confirmed present in `vendor/laravel/framework`. If any were removed, the plan halts here and a bigger migration plan is required.

## Implementation Notes
*(left blank — filled by the executor)*
