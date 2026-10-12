# Lab 1 — Shoppn

This folder contains the same Shoppn source as `lab-2-shoppn/`, included in both lab folders at the owner’s request. It is not presented as a different earlier implementation.

A PHP and MySQL e-commerce application built using an MVC-inspired architecture. Implemented lab scope: **Tasks 1–10** of the Vanilla PHP pathway.

## Features

- Customer registration with password confirmation, strong-password validation, CSRF protection, bcrypt hashing and duplicate-email checking.
- Secure login/logout, session regeneration and role-based administrator access.
- Brand and category management: add, list and edit.
- Product management with category/brand dropdowns and image uploads validated by actual file type and size.
- Product edits preserve the current image unless a replacement is uploaded.
- Product listings, category/brand filtering, product details and title/keyword search.
- Responsive photographic homepage with the existing cream, charcoal and deep-green styling.

Shopping cart, checkout and payment are **not implemented**. The Add to Cart control displays an unavailable message; Tasks 11–13 are outside scope.

## Local setup with XAMPP

1. Install XAMPP with PHP 7.4 or newer, preferably a maintained PHP 8.x version. Enable `mysqli` with `mysqlnd`, `fileinfo` and sessions.
2. Copy this folder to `htdocs/shoppn_new/` and start Apache and MySQL/MariaDB.
3. In phpMyAdmin, create a **new empty database**, such as `shoppn_lab2`.
4. Import `database/schema.sql` into that new database. It contains schema only, with no customer records, password hashes or destructive DROP statements. Do not import it over a populated database.
5. Copy `core/db_cred.example.php` to `core/db_cred.php`. Set your own database host, user, password and database name. The real configuration is ignored by Git.
6. Ensure Apache can write to `images/products/` for uploads and to its PHP session directory. Use permissions appropriate to your server; do not make the project world-writable.
7. Open `http://localhost/shoppn_new/`. Links derive their mount path from the executing entry point, so a differently named folder is also supported.

The schema starts with no products, brands, categories or customers. Product images are included as assets; product records must be added through the admin form.

## Administrator setup

Register an account first. New customers always receive role 2. To make **your own chosen account** an administrator in your own newly configured database, run this in phpMyAdmin after replacing the email:

```sql
UPDATE customer SET user_role = 1 WHERE customer_email = 'YOUR_REGISTERED_EMAIL';
```

Log out and back in to refresh the role in the session. Admin navigation then exposes brand, category and product management. Add a brand and category before adding a product. Ordinary customers cannot access admin views or actions.

## Structure

`classes/` contains SQL/model logic; `controllers/` call models; `actions/` process forms and redirect; `views/` render HTML; `core/` provides configuration, sessions and helpers. Assets live in `css/`, `js/` and `images/`.

## Offline checks

From this lab folder:

```sh
php tests/registration_test.php
node tests/registration_client_test.js
node tests/forms_client_test.js
```

These tests use doubles and do not connect to the database or insert records. PHP and JavaScript syntax checks were also run on the publication copy. Browser and live database tests should be repeated after configuring your own environment.

## Publication contents

Real configuration, logs, deployment checks, local integration-test results and raw database exports are excluded. The unused React/Vite scaffold is excluded because it is not the implemented Vanilla lab. No production customer data or password hashes are included.
