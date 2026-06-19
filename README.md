# BookHub

BookHub is a plain PHP and MySQL web app for browsing, renting, selling, and managing books.

## Correct Folder Structure

```text
bookhub/
├── adminpannel/          Admin dashboard pages
├── config/               Database connection
├── css/                  Stylesheets
├── database/             SQL setup files
├── docs/                 Extra project notes from the original package
├── js/                   Frontend JavaScript
├── *.php                 Main website pages and actions
├── index.html            Redirects to index.php
└── README.md             This setup guide
```

The original zip included duplicate `.git` folders, an empty nested `bookhub` folder, an empty `BOOOKHUB` folder, and placeholder files named `images` and `Form/HTML`. Those were removed from this cleaned version.

## Requirements

- XAMPP, WAMP, Laragon, or another local PHP + MySQL server
- PHP 8.x recommended
- MySQL or MariaDB

## How To Run With XAMPP

1. Copy this `bookhub` folder into:

   ```text
   C:\xampp\htdocs\bookhub
   ```

2. Start XAMPP.

3. Start `Apache` and `MySQL`.

4. Open phpMyAdmin:

   ```text
   http://localhost/phpmyadmin
   ```

5. Import the database file:

   ```text
   C:\xampp\htdocs\bookhub\database\database.sql
   ```

   The SQL file creates the `bookhub` database and all required tables.

6. Check the database settings in `config/db.php`.

   Default settings:

   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "bookhub";
   ```

7. If your MySQL password or port is different, copy:

   ```text
   config\local.example.php
   ```

   to:

   ```text
   config\local.php
   ```

   Then edit `config/local.php`.

8. Open the setup checker:

   ```text
   http://localhost/bookhub/check_setup.php
   ```

   It must say `Database connected successfully`.

9. Open the app:

   ```text
   http://localhost/bookhub/
   ```

## Login Details

Admin login:

```text
Email: admin@bookhub.com
Password: admin123
```

Admin panel:

```text
http://localhost/bookhub/adminpannel/dashbord.php
```

## Useful Pages

```text
http://localhost/bookhub/register.php
http://localhost/bookhub/login.php
http://localhost/bookhub/browse_books.php
http://localhost/bookhub/rent.php
http://localhost/bookhub/sell.php
http://localhost/bookhub/shopingcart.php
```

## Troubleshooting

- If you see `Database Connection Failed`, make sure MySQL is running and `config/db.php` matches your local MySQL username/password.
- If pages load but no books appear, import `database/database.sql` again in phpMyAdmin.
- If admin login fails, confirm the `users` table contains `admin@bookhub.com`.
- If XAMPP MySQL will not start, another MySQL service may already be using port `3306`. Stop the other MySQL service or set XAMPP MySQL to another port and update `config/local.php`.
