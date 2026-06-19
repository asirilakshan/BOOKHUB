<?php
require_once __DIR__ . '/config/session.php';
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];
$cart_id = $_POST['cart_id'] ?? '';

if(!empty($cart_id)){
    $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt){
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: shopingcart.php");
exit();
?>
