<?php
require_once __DIR__ . '/../config/session.php';
require '../config/db.php';

// Check if user is admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$user_id = $_POST['user_id'] ?? '';

if(!empty($user_id)){
    // Delete user's cart items first
    $conn->query("DELETE FROM cart WHERE user_id = " . intval($user_id));
    
    // Then delete the user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt){
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: users.php");
exit();
?>
