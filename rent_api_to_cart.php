<?php
require_once __DIR__ . '/config/session.php';
require 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: rent.php");
    exit();
}

$user_id = $_SESSION['user'];
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? 'Unknown author');
$price = (float)($_POST['price'] ?? 0);
$rental_days = (int)($_POST['rental_days'] ?? 7);

if($title === '' || $price <= 0 || $rental_days <= 0){
    header("Location: rent.php?error=Invalid rental book");
    exit();
}

$book_id = null;
$find_sql = "SELECT id FROM books WHERE title = ? AND author = ? AND book_type = 'rent' LIMIT 1";
$find_stmt = $conn->prepare($find_sql);

if($find_stmt){
    $find_stmt->bind_param("ss", $title, $author);
    $find_stmt->execute();
    $find_result = $find_stmt->get_result();
    $book = $find_result->fetch_assoc();
    $find_stmt->close();

    if($book){
        $book_id = (int)$book['id'];
    }
}

if(!$book_id){
    $insert_sql = "INSERT INTO books (title, author, price, book_type) VALUES (?, ?, ?, 'rent')";
    $insert_stmt = $conn->prepare($insert_sql);

    if(!$insert_stmt){
        header("Location: rent.php?error=Could not prepare rental book");
        exit();
    }

    $insert_stmt->bind_param("ssd", $title, $author, $price);

    if(!$insert_stmt->execute()){
        $insert_stmt->close();
        header("Location: rent.php?error=Could not save rental book");
        exit();
    }

    $book_id = $insert_stmt->insert_id;
    $insert_stmt->close();
}

$cart_sql = "INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, 1)
             ON DUPLICATE KEY UPDATE quantity = quantity + 1";
$cart_stmt = $conn->prepare($cart_sql);

if(!$cart_stmt){
    header("Location: rent.php?error=Could not prepare cart");
    exit();
}

$cart_stmt->bind_param("ii", $user_id, $book_id);

if(!$cart_stmt->execute()){
    $cart_stmt->close();
    header("Location: rent.php?error=Could not add rental to cart");
    exit();
}

$cart_stmt->close();

$rental_sql = "INSERT INTO rentals (user_id, book_id, rental_days, rental_date, due_date)
               VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))";
$rental_stmt = $conn->prepare($rental_sql);

if($rental_stmt){
    $rental_stmt->bind_param("iiii", $user_id, $book_id, $rental_days, $rental_days);
    $rental_stmt->execute();
    $rental_stmt->close();
}

header("Location: shopingcart.php?success=Rental book added to cart");
exit();
