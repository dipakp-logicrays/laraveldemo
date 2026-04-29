# Project Overview

## Project Name
LaravelDemo

## Description
A beginner-friendly Laravel application built to learn and practice the core fundamentals of the Laravel framework — MVC, migrations, seeders, Blade templates, database interactions, and artisan commands. Used as a hands-on reference for working through Laravel features end-to-end.

## Tech Stack
- **Framework**: Laravel ^12
- **Language**: PHP ^8.2
- **Database**: MySQL / MariaDB
- **Web Server**: Apache (recommended) or Laravel's built-in `php artisan serve`
- **Authentication**: Laravel Breeze + Laravel Sanctum
- **Frontend Build**: Vite ^5
- **CSS**: Tailwind CSS 3 (with `@tailwindcss/forms`)
- **JS**: Alpine.js, Axios
- **Testing**: PHPUnit ^11, Mockery, Faker
- **Linting**: Laravel Pint
- **Error Reporting**: Spatie Laravel Ignition, Nuno Maduro Collision

## Project Type
Server-rendered Laravel web application with a Blade frontend and Vite-bundled assets. Includes a small set of REST endpoints (FAQs) consumed via API integration.

## Features
- Blog system
- Product list
- Contacts CRUD with email notifications
- FAQs CRUD with API integration

## Target Developers
Developers learning Laravel from scratch using a real project structure. Code should favor **clarity and idiomatic Laravel** over clever abstractions, since the project doubles as a reference for Laravel fundamentals.

## Local Path
`/var/www/html/laraveldemo/`

## Local URL
`http://laraveldemo.local` (via Apache vhost) or `http://127.0.0.1:8000` (via `php artisan serve`)

## Login
- URL: `http://laraveldemo.local/login`
- Email: `dipakp@logicrays.com`
- Password: `dipak@123`

## Official Resources
- Laravel docs: https://laravel.com/docs
- Laravel Breeze: https://laravel.com/docs/starter-kits#laravel-breeze
- Laravel Sanctum: https://laravel.com/docs/sanctum
- Pint: https://laravel.com/docs/pint
- Tailwind: https://tailwindcss.com/docs
- Alpine.js: https://alpinejs.dev/
