# BookHub - Setup & Troubleshooting Guide

## ✅ Issues Fixed

### 1. **Login Page** ✓
- Fixed login button form submission
- Added proper action attribute to form
- Login now correctly checks database for user credentials
- Uses `password_verify()` for secure authentication

### 2. **Admin Panel Database Errors** ✓
- Fixed `$conn` undefined errors in all admin panel files
- Changed `include` to `require` with connection validation
- Admin dashboard now properly connects to database

### 3. **Browse/Buy Books Functionality** ✓
- Created new `browse_books.php` page with dynamic book listing
- Books now load from database instead of static HTML
- Added "Add to Cart" button for buying books
- Added "Rent Now" button linking to rental page

### 4. **Book Rental System** ✓
- `rent.php` loads all available rental books from database
- Proper prepared statements for security
- Handles rental duration and due date calculation

---

## 🚀 IMPORTANT: Database Setup Required

**Your application will NOT work without setting up the database.** Follow these steps:

### Step 1: Open phpMyAdmin
1. Go to `http://localhost/phpmyadmin` in your browser
2. Log in with default credentials (username: `root`, password: empty)

### Step 2: Import Database
1. Click the **"Import"** tab at the top
2. Click **"Choose File"** button
3. Navigate to: `C:\xampp\htdocs\bookhub\database.sql`
4. Click **"Import"** button
5. You should see success message

**Alternative (Command Line):**
```bash
mysql -u root < C:\xampp\htdocs\bookhub\database.sql
```

### Step 3: Verify Database Created
1. In phpMyAdmin, refresh the left sidebar
2. You should see a **"bookhub"** database
3. Click on it and verify these tables exist:
   - `users` (contains admin@bookhub.com user)
   - `books` (empty - add books through UI)
   - `cart` (empty - used for shopping)
   - `rentals` (empty - used for rentals)
   - `contacts` (empty - contact form)

---

## 🔐 Default Admin Account

After database import, you can log in as admin:
- **Email:** `admin@bookhub.com`
- **Password:** `admin123`

Go to: `http://localhost/bookhub/adminpannel/dashbord.php` (after logging in)

---

## 📖 How to Add Books to Your Store

### Option 1: Through the Sell Page
1. Go to: `http://localhost/bookhub/sell.php`
2. Register a new account
3. Fill in book details (title, author, price, type)
4. Books will appear in the browse page

### Option 2: Through Admin Panel
1. Log in as admin
2. Go to Admin Dashboard
3. Use Books management section to add books

### Option 3: Direct Database Insert
In phpMyAdmin, go to `bookhub` → `books` → Insert and add:
```
title: "The Great Gatsby"
author: "F. Scott Fitzgerald"
price: 5.00
book_type: "rent"
```

---

## 🧪 Testing Checklist

### ✅ Test Login
1. Go to: `http://localhost/bookhub/login.php`
2. Login with: `admin@bookhub.com` / `admin123`
3. Should redirect to homepage
4. Should see logout button in nav

### ✅ Test Register
1. Go to: `http://localhost/bookhub/register.php`
2. Create new account with email and password
3. Should be able to login with new account

### ✅ Test Browse Books
1. Go to: `http://localhost/bookhub/browse_books.php` (OR click "Browse Books" on homepage)
2. If no books show, add books first using "Sell a Book" page
3. Should show all books from database

### ✅ Test Buy Books
1. Log in first
2. Go to Browse Books page
3. Click "Add to Cart" on any book
4. Go to Shopping Cart (`http://localhost/bookhub/shopingcart.php`)
5. Should see cart with items and total price

### ✅ Test Rent Books
1. Log in first
2. Go to: `http://localhost/bookhub/rent.php`
3. Select a book and rental duration
4. Click "Rent Book"
5. Should show success message

### ✅ Test Sell/Add Books
1. Log in first
2. Go to: `http://localhost/bookhub/sell.php`
3. Fill in book details and submit
4. Book should appear in browse_books.php

### ✅ Test Admin Panel
1. Log in as admin
2. Go to: `http://localhost/bookhub/adminpannel/dashbord.php`
3. Should see dashboard with books, users, orders stats

---

## ❌ Troubleshooting

### Problem: "Login page not working"
**Solution:**
1. Verify database was imported (check phpMyAdmin)
2. Check if `config/db.php` has correct credentials:
   - Servername: `localhost` ✓
   - Username: `root` ✓
   - Password: `` (empty) ✓
   - Database: `bookhub` ✓
3. Try using admin credentials: `admin@bookhub.com` / `admin123`

### Problem: "Can't see any books to rent/buy"
**Solution:**
1. Go to `sell.php` and add some books first
2. OR manually insert books in phpMyAdmin
3. Then go to `browse_books.php` to see them

### Problem: "Can't add books to cart"
**Solution:**
1. Make sure you're logged in first
2. Check shopping cart page to see if items were added
3. Clear browser cookies if issues persist

### Problem: "Admin panel shows errors"
**Solution:**
1. Verify you're logged in as admin
2. Check database connection is working
3. Go to admin dashboard: `http://localhost/bookhub/adminpannel/dashbord.php`

### Problem: "Database connection failed"
**Solution:**
1. Check if MySQL/XAMPP is running
2. Verify database name is `bookhub` (lowercase)
3. Verify `database.sql` was imported successfully
4. Check `config/db.php` credentials match your MySQL setup

---

## 📱 Features Overview

### For Regular Users
- ✅ Register new account
- ✅ Login/Logout
- ✅ Browse all available books
- ✅ Add books to shopping cart
- ✅ Checkout and purchase
- ✅ Rent books short-term
- ✅ View order history
- ✅ Sell your own books

### For Admins
- ✅ View all users
- ✅ View all books
- ✅ View all orders
- ✅ Delete books, users, orders
- ✅ See dashboard statistics

---

## 📧 Support

If you encounter issues:
1. Check all database tables are created (5 tables total)
2. Verify default admin user exists
3. Test with credentials: `admin@bookhub.com` / `admin123`
4. Check browser console (F12) for JavaScript errors
5. Check XAMPP error logs

---

**Last Updated:** After all critical fixes  
**Database Version:** Current with all tables and admin user
