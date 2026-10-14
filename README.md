# NovaFood — Food Delivery Management System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-WampServer-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://wampserver.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/Tests-24%20Passed-emerald?style=for-the-badge&logo=githubactions&logoColor=white)]()

**NovaFood** is a restaurant and food delivery web application built with **Laravel**, **MySQL (WampServer)**, **Blade templates**, and **Tailwind CSS**. It is tailored for the Philippine market with Philippine Peso (₱) pricing, Cash on Delivery (COD) workflows, live food order tracking, and an administrative portal.

---

## 🍽️ Key Features

### 🌟 Public & Customer Experience
- **Public Homepage (`/`)**: Hero banner, live food category browser, chef-featured items, promotional deals, and search field.
- **Menu Catalog (`/menu`)**: Live search, category filtering, price sorting (low to high, high to low), and inventory availability badges.
- **Product Details (`/menu/{food}`)**: High-resolution food photography, ingredient descriptions, interactive quantity picker, and related items.
- **Cart Management (`/cart`)**: Real-time recalculation of prices, item additions, quantity modifications, item deletion, and cart clearing. Guest carts are merged into customer accounts upon login.
- **Transactional Checkout (`/checkout`)**: Multi-step checkout with server-side pricing recalculation, inventory locking to prevent race conditions, Cash on Delivery (COD) support, and free delivery thresholds (orders over ₱800).
- **Order Confirmation & Tracking (`/orders/{order}`)**: Human-readable order numbers (e.g. `NF-20261014-XXXX`), customer and address snapshots, itemized line totals, estimated arrival time, and a real-time status update audit trail.
- **Customer Dashboard (`/dashboard`)**: Active order monitor, order history, spending statistics, and profile management.
- **Order Cancellation**: Customers can cancel orders that are still pending or confirmed.

### 🛡️ Administrative Portal (`/admin`)
- **Protected by Role-Based Authorization**: Customer accounts attempting to visit `/admin` routes receive an HTTP 403 Forbidden response.
- **Admin Dashboard (`/admin/dashboard`)**: Real-time sales statistics, net revenue from delivered orders, order volume, live kitchen/transit counts, recent order table, and low-stock inventory alerts.
- **Food Catalog Management (`/admin/foods`)**: Complete CRUD operations, category assignment, price configuration, stock quantity management, image uploads stored in `storage/app/public/foods`, and safe soft deletions to preserve historical order records.
- **Category Management (`/admin/categories`)**: Create, edit, and safely delete categories with automated verification preventing deletion if active food products belong to the category.
- **Order Lifecycle Management (`/admin/orders`)**: Filter orders by status, search by customer or order number, update delivery status using an enforced state machine (Pending → Confirmed → Preparing → Ready for Pickup → Out for Delivery → Delivered), and COD collection verification.
- **Customer & User Accounts (`/admin/users`)**: Search registered users, inspect order histories and total food spending, and toggle active status (with safeguards preventing admins from self-deactivation or locking out the last admin).

---

## 💻 Technology Stack

- **Backend**: Laravel 12.x / PHP 8.3
- **Database**: MySQL 5.7+ / 8.0+ running via WampServer
- **Frontend**: Laravel Blade, Vanilla JavaScript, and Tailwind CSS (configured with dark violet, purple, and warm orange brand tokens)
- **Authentication**: Laravel Session Auth with bcrypt hashing and login rate limiting
- **Testing**: PHPUnit / Laravel Test Suite (24 automated tests with 84 assertions)

---

## ⚙️ System Requirements

- Windows 10/11 with PowerShell
- WampServer (Apache + MySQL running on port `3306`)
- PHP >= 8.2 (configured with `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`)
- Composer >= 2.x
- Git

---

## 🚀 Setup & Installation Guide

Follow these PowerShell commands to set up and run NovaFood locally:

### 1. Database Setup via WampServer / phpMyAdmin
1. Start **WampServer** and ensure the WampServer icon in the system tray turns **Green**.
2. Open **phpMyAdmin** in your browser at `http://localhost/phpmyadmin` (default user: `root`, password: empty).
3. Create a new database named:
   ```sql
   CREATE DATABASE novafood_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

### 2. Configure Environment (`.env`)
Copy `.env.example` to `.env`:
```powershell
cp .env.example .env
```

Ensure your `.env` contains the following database configuration:
```env
APP_NAME=NovaFood
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=novafood_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Generate Application Key
```powershell
php artisan key:generate
```

### 4. Run Migrations & Seed Database
Execute the database migrations and seed default categories, sample dishes, and demo users:
```powershell
php artisan migrate --seed
```

### 5. Link Public Storage
Enable public access for uploaded food images:
```powershell
php artisan storage:link
```

### 6. Create Initial Administrator Account
You can create an administrator using the custom Artisan command:
```powershell
php artisan novafood:make-admin
```
Follow the interactive prompts to set the administrator's name, email, and password.

---

## 🔑 Demo Credentials

For quick local testing, the seeder automatically sets up the following accounts:

| Role | Email | Password |
|---|---|---|
| **Administrator** | `admin@novafood.com` | `Admin12345!` |
| **Demo Customer** | `customer@novafood.com` | `Customer123!` |

---

## 🌐 Running the Application Locally

Start the local Laravel development server:
```powershell
php artisan serve
```

Visit the application in your browser:
- **Public Storefront**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Menu Catalog**: [http://127.0.0.1:8000/menu](http://127.0.0.1:8000/menu)
- **Customer Sign In**: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
- **Admin Portal**: [http://127.0.0.1:8000/admin/dashboard](http://127.0.0.1:8000/admin/dashboard)

---

## 🧪 Running Automated Tests

Run the test suite using PHPUnit:
```powershell
php artisan test
```

Or view detailed test outcomes with TestDox:
```powershell
php artisan test --testdox
```

### Test Coverage Highlights:
- **PublicPagesTest**: Homepage, menu catalog, live food search, and product details.
- **AuthenticationTest**: Valid/invalid registration, default role enforcement, login rate limiting, session regeneration, and logout.
- **AuthorizationTest**: HTTP 403 enforcement on admin routes, guest redirection, and cross-customer order viewing denial.
- **CartAndCheckoutTest**: Cart manipulation, quantity limits, server-side price recalculation (preventing client price spoofing), transactional orders, and atomic stock decrements.
- **AdminManagementTest**: Product CRUD, category CRUD, and state machine transition validation.

---

## 🔒 Security Measures

- **No Secrets in Version Control**: `.env` is excluded in `.gitignore`; `.env.example` contains safe placeholders.
- **Server-Side Pricing**: Prices and grand totals are calculated on the server using database records; client-submitted totals are ignored.
- **Role Assignment Lockdown**: Public registration forms cannot supply role values; all registrations default to `customer`.
- **Database Transactions & Row Locking**: Order placement utilizes `DB::transaction()` and `lockForUpdate()` to avoid inventory race conditions.
- **Cross-Site Request Forgery (CSRF)**: All forms enforce `@csrf`.
- **Authentication Throttling**: Login requests are rate-limited to prevent brute-force attacks.

---

## 🛠️ Troubleshooting

| Issue | Solution |
|---|---|
| **MySQL connection refused** | Verify WampServer is running and the icon is green. Confirm MySQL is listening on port `3306`. |
| **Unknown database 'novafood_db'** | Create `novafood_db` in phpMyAdmin (`http://localhost/phpmyadmin`). |
| **SQLSTATE 1071: Specified key was too long** | Handled automatically in `AppServiceProvider` with `Schema::defaultStringLength(191)` and `InnoDB` engine. |
| **Storage images 404** | Run `php artisan storage:link`. |
