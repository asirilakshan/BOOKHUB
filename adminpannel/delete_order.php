<?php
require_once __DIR__ . '/../config/session.php';
require '../config/db.php';

// Check if user is admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$order_id = $_POST['order_id'] ?? '';

if(!empty($order_id)){
    $sql = "DELETE FROM cart WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt){
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: orders.php");
exit();
?>
