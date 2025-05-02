<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

# Laravel Demo Project

Welcome to the **LaravelDemo** repository! This project is a beginner-friendly Laravel application to understand the core fundamentals of Laravel framework step-by-step.

GitHub Repo: [https://github.com/dipakp-logicrays/laraveldemo](https://github.com/dipakp-logicrays/laraveldemo)

---

## 📁 Project Information
- **Project Name**: LaravelDemo
- **Framework**: Laravel 10+
- **Purpose**: Practice Laravel basics — MVC, migrations, seeders, Blade templates, database, and artisan commands.
- **Local Path**: `/var/www/html/laraveldemo/`

---

## 🧰 Requirements
- PHP 8.x
- Composer
- MySQL or MariaDB
- Laravel CLI (`laravel installer`)
- Git

---

## 🚀 Installation Instructions

```bash
# Step 1: Clone the project
cd /var/www/html/
git clone https://github.com/dipakp-logicrays/laraveldemo.git

# Step 2: Navigate into project directory
cd laraveldemo

# Step 3: Install dependencies
composer install

# Step 4: Copy environment file
cp .env.example .env

# Step 5: Generate app key
php artisan key:generate

# Step 6: Setup database (create manually or via MySQL)
Update your .env file with correct DB info:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laraveldemo
DB_USERNAME=root
DB_PASSWORD=your_password
```

## 🗃️ MySQL Database Setup

### Create Database:

```bash
CREATE DATABASE laraveldemo;
```

### Import SQL (optional backup):
```bash
mysql -u root -p laraveldemo < backup.sql
```


### Export SQL::
```bash
mysqldump -u root -p laraveldemo > backup.sql
```

## ⚙️ Laravel Artisan Commands Guide

### ✔ Common Artisan Commands

```bash
php artisan list                        # List all commands
php artisan route:list                 # View registered routes
php artisan serve                      # Start local server
php artisan config:cache               # Cache config files
php artisan migrate                    # Run all migrations
php artisan db:seed                    # Run seeders
php artisan migrate:refresh --seed    # Reset DB and seed again
```

### 🛠 Generate Files
```bash
php artisan make:controller ProductController
php artisan make:model Product -mf       # Model with migration and factory
php artisan make:migration create_products_table
php artisan make:seeder ProductSeeder
php artisan make:factory ProductFactory --model=Product
```

## 🔄 Laravel Project Flow Cycle (Basic)

Request -> Route -> Controller -> Model -> DB -> View (Blade)

## 🙏 Credits

Created by Dipak for learning Laravel from scratch using real project structure and flow.


## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
