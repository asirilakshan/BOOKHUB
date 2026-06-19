<?php
require_once __DIR__ . '/config/session.php';
include 'config/db.php';

if(isset($_POST['add_to_cart'])){
    if(!isset($_SESSION['user'])){
        header("Location: login.php");
        exit();
    }
    
    $book_id = $_POST['book_id'] ?? '';
    $user_id = $_SESSION['user'];
    
    if(!empty($book_id)){
        $sql = "INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, 1) 
                ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $stmt = $conn->prepare($sql);
        
        if($stmt){
            $stmt->bind_param("ii", $user_id, $book_id);
            if($stmt->execute()){
                header("Location: shopingcart.php?success=Book added to cart");
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
