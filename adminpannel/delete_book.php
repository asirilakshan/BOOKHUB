<?php
require_once __DIR__ . '/../config/session.php';
require '../config/db.php';

// Check if user is admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$book_id = $_POST['book_id'] ?? '';

if(!empty($book_id)){
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt){
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: books.php");
exit();
?>
