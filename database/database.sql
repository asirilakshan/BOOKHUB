-- BookHub Database Schema
-- Create this database and tables using phpMyAdmin or MySQL command line

-- Create database
CREATE DATABASE IF NOT EXISTS bookhub;
USE bookhub;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Repair older local databases that were created before admin support existed.
ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin INT DEFAULT 0;

-- Books table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    book_type ENUM('sell', 'rent') NOT NULL,
    image VARCHAR(255) NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    UNIQUE KEY user_book (user_id, book_id)
);

-- Rentals table
CREATE TABLE IF NOT EXISTS rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    rental_days INT NOT NULL,
    rental_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    due_date DATETIME NOT NULL,
    return_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- Contacts table
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create an admin user (password: admin123)
INSERT INTO users (fullname, email, password, is_admin)
SELECT 'Admin', 'admin@bookhub.com', '$2y$10$KbWoAW2ghlAxE2LwkYOl3eeWRUEQUKpEHT3zztfmfFo209FrTZYAW', 1
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@bookhub.com');

UPDATE users
SET password = '$2y$10$KbWoAW2ghlAxE2LwkYOl3eeWRUEQUKpEHT3zztfmfFo209FrTZYAW',
    is_admin = 1
WHERE email = 'admin@bookhub.com';
