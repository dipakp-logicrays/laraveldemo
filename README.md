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

---

# Laravel Demo Project

Welcome to the **LaravelDemo** repository! This project is a beginner-friendly Laravel application to understand the core fundamentals of Laravel framework step-by-step.

GitHub Repo: [https://github.com/dipakp-logicrays/laraveldemo](https://github.com/dipakp-logicrays/laraveldemo)

---

## 1. 📁 Project Information

- **Project Name**: LaravelDemo
- **Framework**: Laravel 10+
- **Purpose**: Practice Laravel basics — MVC, migrations, seeders, Blade templates, database, and artisan commands.
- **Local Path**: `/var/www/html/laraveldemo/`

---

## 2. Features

- Blog system
- Product List
- Contacts - CRUD Operation with send email
- FAQs - CRUD operation with API Integration

---

## 3. 🧰 Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL or MariaDB
- Apache (recommended) or Laravel's built-in server
- Git

---

## 4. 🚀 Installation Instructions (Step-by-Step)

Follow these steps to set up the project on your system:

### Step 1: Clone the project

```bash
cd /var/www/html/
git clone https://github.com/dipakp-logicrays/laraveldemo.git
cd laraveldemo
```

### Step 2: Install PHP dependencies

```bash
composer install
```

### Step 3: Install Node.js dependencies

```bash
npm install
```

### Step 4: Copy environment file and generate app key

```bash
cp .env.example .env
php artisan key:generate
```

### Step 5: Configure database in `.env`

Open the `.env` file and update the database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laraveldemo
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 6: Create database and import SQL

The database SQL file is included in the repository at `db/laraveldemo.sql`. Create the database and import it:

```bash
# Login to MySQL and create the database
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS laraveldemo;"

# Import the database
mysql -u root -p laraveldemo < db/laraveldemo.sql
```

> **Note:** If you prefer to start with a fresh database instead, you can run migrations:
> ```bash
> php artisan migrate --seed
> ```

### Step 7: Create storage symlink

This creates a symbolic link from `public/storage` to `storage/app/public` so uploaded images are accessible from the browser:

```bash
php artisan storage:link
```

### Step 8: Build frontend assets

```bash
npm run build
```

---

## 5. Local Environment Setup

### Step 9: Add Host Entry

Add the following line to `/etc/hosts`:

```
# Laravel demo
127.0.0.1 laraveldemo.local
```

### Step 10: Apache Configuration

Add the following line to `/etc/apache2/sites-available/000-default.conf`:

```
# Laravel project setup
Include /var/www/html/laraveldemo/proxy-le-ssl.conf
```

Then restart Apache:

```bash
sudo systemctl restart apache2
```

### Step 11: Open the project

Visit http://laraveldemo.local in your browser. You're all set!

---

## 6. 🏃 Running the Project

After installation, run these commands every time you start working on the project:

```bash
# Start Vite dev server (for frontend hot-reloading with Tailwind CSS)
npm run dev
```

If you are using Apache with the virtual host setup, the project will be available at:
- http://laraveldemo.local

If you are **not** using Apache, you can use Laravel's built-in server:

```bash
# Start Laravel development server
php artisan serve
```

### Quick Reference — Commands to Run After `git pull`

```bash
composer install          # Install any new PHP packages
npm install               # Install any new Node.js packages
php artisan migrate       # Run any new database migrations
npm run build             # Rebuild frontend assets (or use `npm run dev` for development)
```

### Useful Commands During Development

```bash
npm run dev               # Start Vite dev server (hot-reload for CSS/JS changes)
npm run build             # Build production-ready frontend assets
php artisan optimize      # Cache config, routes, and views
php artisan optimize:clear # Clear all cached files
```

---

## 7. Login Details

- **URL:** http://laraveldemo.local/login
- **Email:** dipakp@logicrays.com
- **Password:** dipak@123

---

## 8. ⚙️ Laravel Artisan Commands Guide

### 🛠 Generate Files

```bash
php artisan make:controller ProductController
php artisan make:model Product -mf       # Model with migration and factory
php artisan make:migration create_products_table
php artisan make:seeder ProductSeeder
php artisan make:factory ProductFactory --model=Product
```

---

## 9. 🔄 Laravel Project Flow Cycle (Basic)

Request -> Route -> Controller -> Model -> DB -> View (Blade)

---

## 10. 🙏 Credits

Created by Dipak for learning Laravel from scratch using real project structure and flow.

---

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
