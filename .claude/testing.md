# Testing Configuration

## Test Framework
PHPUnit ^11 (with Laravel's testing helpers, Mockery for mocks, Faker for fixtures).

## TDD Methodology

Each task follows strict Red → Green → Refactor:

1. Write a failing test for one requirement.
2. Write the minimum code to pass.
3. Refactor while tests stay green.
4. Repeat for the next requirement.
5. Commit when the task is complete.

## Commands

```bash
# Run all tests (default)
./vendor/bin/phpunit

# Laravel-flavored runner with nicer output
php artisan test

# Run a single test file
./vendor/bin/phpunit tests/Feature/ExampleTest.php

# Run a single test method by filter
./vendor/bin/phpunit --filter test_method_name

# Run only a suite
./vendor/bin/phpunit --testsuite=Unit
./vendor/bin/phpunit --testsuite=Feature

# Run with coverage (requires Xdebug or PCOV)
XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-text
XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html coverage/
```

## Parallel Execution
Parallelism is **not enabled by default** in this project. To opt in, install `brianium/paratest` and run:

```bash
composer require --dev brianium/paratest
php artisan test --parallel
```

Until then, tests run serially via `./vendor/bin/phpunit`.

## Test Configuration
Defined in `phpunit.xml`:

- `APP_ENV=testing`
- `BCRYPT_ROUNDS=4` (faster auth tests)
- `CACHE_DRIVER=array`
- `MAIL_MAILER=array` (no real mail sent during tests)
- `QUEUE_CONNECTION=sync`
- `SESSION_DRIVER=array`

> The SQLite in-memory fallback is **commented out** in `phpunit.xml`. If you want test isolation against an in-memory database, uncomment the `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:` env entries.

## Test File Locations
- Unit tests: `tests/Unit/`
- Feature/Integration tests: `tests/Feature/`
- Test mirror structure: tests should mirror the `app/` directory layout.

## Coverage Requirements
- **Target minimum: 80%** for new application code (`app/`).
- New features and bug fixes should ship with tests.
- Generate HTML reports into `coverage/` (gitignored) for local review.

## Test Naming Convention
- Test classes: `{ClassUnderTest}Test.php`, extending `Tests\TestCase` (Feature) or `PHPUnit\Framework\TestCase` (pure Unit).
- Methods: snake_case prefixed with `test_`, or use the `#[Test]` attribute / `/** @test */` annotation. Example: `test_user_can_log_in()`.
- Group related tests with `@group` or `#[Group('feature-name')]`.

## Common Patterns
- Use `RefreshDatabase` trait for tests that touch the database.
- Use `$this->actingAs($user)` to authenticate within feature tests.
- Use Laravel's HTTP test helpers (`$this->get`, `$this->postJson`, `$this->assertDatabaseHas`).
- Fake services with `Mail::fake()`, `Notification::fake()`, `Queue::fake()`, `Storage::fake()`.
- Use Mockery for collaborator mocks; prefer Laravel's facade fakes for framework services.
- Use Factories (`User::factory()->create()`) over hand-rolling fixtures.
