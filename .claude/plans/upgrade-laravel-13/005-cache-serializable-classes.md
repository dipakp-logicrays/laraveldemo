# Task 005: Review cache serialization for `serializable_classes` allow-list

**Status**: pending
**Depends on**: 002
**Retry count**: 0

## Description
Laravel 13 flips the default for the cache's `serializable_classes` config to `false` (was effectively unrestricted). Any code that stores a non-scalar object (Eloquent model, DTO, custom value object) in the cache will throw at hydration time unless the class FQCN is added to an allow-list in `config/cache.php`. The same hardening applies to **queued job payloads**: any job dispatched with an Eloquent model or arbitrary object as a constructor argument may face stricter unserialization. Audit BOTH the cache API and the queue dispatch sites; configure the allow-list **only if** something needs it.

## Context
- Files to scan: `app/`, `routes/`, `database/seeders/`.
- Cache APIs to look for: `Cache::put`, `Cache::remember`, `Cache::rememberForever`, `Cache::add`, `cache()->put`, `cache([...])`, `cache()->remember*`, plus any `Repository::put()` on a custom cache repo.
- Queue dispatch APIs to look for: `dispatch(`, `Bus::dispatch`, `Queue::push`, `->onQueue(`, `SomeJob::dispatch(...)`, `SomeJob::dispatchSync(...)`, `SomeJob::dispatchAfterResponse(...)`.
- Known callsite: `app/Listeners/SendContactNotification.php` line 25 — `SendContactEmail::dispatch($event->contact);` dispatches an Eloquent `Contact` model. Eloquent models with the `SerializesModels` trait re-fetch by ID so the payload only carries `[class, id]`, not the full row — this is generally safe. But the listener should be confirmed in Implementation Notes.
- Default `config/cache.php` may need a new `'serializable_classes' => [App\Models\Foo::class]` key.

## Verifications
- [ ] `grep -rnE '(Cache::|cache\()' app/ routes/ database/ 2>/dev/null` lists every cache-write site.
- [ ] For each cache site, classify the value being cached as **scalar/array** (safe — no action needed) or **object** (action needed).
- [ ] `grep -rnE '(dispatch\(|Bus::dispatch|Queue::push|::dispatchSync|::dispatchAfterResponse)' app/ routes/ database/ 2>/dev/null` lists every queue-dispatch site.
- [ ] For each dispatch site, document the constructor argument types in Implementation Notes. Confirm any job receiving an Eloquent model uses the `SerializesModels` trait (default for Laravel jobs scaffolded via `php artisan make:job`).
- [ ] If at least one cache site stores an object: open `config/cache.php`, add a `'serializable_classes' => [...]` array enumerating the FQCNs.
- [ ] If zero objects are cached: do **not** add the config key — leave `config/cache.php` unchanged so we don't add unused configuration.
- [ ] If the config was edited: `php artisan config:show cache.serializable_classes` (read-only) prints the array we wrote.

## Acceptance Criteria
- All cache-write sites and queue-dispatch sites are documented in Implementation Notes (with class names if objects).
- `config/cache.php` is either unchanged (no objects cached) or contains an explicit allow-list.
- Each queued job receiving an object is verified to use `SerializesModels` (or a similar safe pattern); flag any that does not in Implementation Notes for operator review — do not silently add the model FQCN to a queue allow-list without confirming the failure mode.
- No untested guess: if a call site's cached type cannot be statically determined, flag it in Implementation Notes for the operator to confirm rather than blindly allow-listing.

## Implementation Notes
*(left blank — filled by the executor)*
