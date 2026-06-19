<?php
require_once __DIR__ . '/config/session.php';
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];
$sql = "SELECT fullname, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - BookHub</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<header>
    <nav class="navbar">
        <a href="index.php" style="text-decoration: none;"><div class="logo">📚 BookHub</div></a>
        <ul class="nav-links" style="gap: 20px;">
            <li><a href="index.php">Home</a></li>
            <li><a href="shopingcart.php">🛒 Cart</a></li>
            <li><a href="rent.php">🔄 Rent</a></li>
            <li><a href="sell.php">💸 Sell</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<!-- Profile Section -->
<section style="padding: 60px 8%; min-height: calc(100vh - 200px);">
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 60px;">👤</span>
            </div>
            <h1 style="font-size: 36px; color: #333; margin-bottom: 10px;"><?php echo htmlspecialchars($user['fullname']); ?></h1>
            <p style="color: #666; font-size: 16px;">BookHub Member</p>
        </div>

        <!-- Profile Information -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); padding: 30px; margin-bottom: 30px;">
            <h2 style="color: #333; font-size: 24px; margin-bottom: 30px; border-bottom: 3px solid #667eea; padding-bottom: 15px;">
                📋 Account Information
            </h2>

            <div style="margin-bottom: 25px;">
                <p style="color: #666; font-size: 14px; margin-bottom: 5px;">Email Address</p>
                <p style="color: #333; font-size: 16px; font-weight: 600;">📧 <?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <div style="margin-bottom: 25px;">
                <p style="color: #666; font-size: 14px; margin-bottom: 5px;">Member Since</p>
                <p style="color: #333; font-size: 16px; font-weight: 600;">📅 <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
            </div>

            <div style="margin-bottom: 25px;">
                <p style="color: #666; font-size: 14px; margin-bottom: 5px;">Account Status</p>
                <p style="color: #2ecc71; font-size: 16px; font-weight: 600;">✅ Active</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <a href="shopingcart.php" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 15px; text-decoration: none; text-align: center; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                <div style="font-size: 32px; margin-bottom: 10px;">🛒</div>
                <h3 style="margin: 0;">My Cart</h3>
                <p style="margin: 5px 0 0; opacity: 0.9;">View shopping cart</p>
            </a>

            <a href="rent.php" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 15px; text-decoration: none; text-align: center; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                <div style="font-size: 32px; margin-bottom: 10px;">🔄</div>
                <h3 style="margin: 0;">Rent Books</h3>
                <p style="margin: 5px 0 0; opacity: 0.9;">Browse rentals</p>
            </a>

            <a href="sell.php" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 15px; text-decoration: none; text-align: center; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                <div style="font-size: 32px; margin-bottom: 10px;">💸</div>
                <h3 style="margin: 0;">Sell Books</h3>
                <p style="margin: 5px 0 0; opacity: 0.9;">List your books</p>
            </a>
        </div>

        <!-- Danger Zone -->
        <div style="background: #fff5f5; border: 2px solid #e74c3c; border-radius: 15px; padding: 25px; text-align: center;">
            <h3 style="color: #e74c3c; margin-bottom: 15px;">⚠️ Danger Zone</h3>
            <a href="logout.php" style="display: inline-block; padding: 12px 30px; background: #e74c3c; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                🚪 Logout
            </a>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="color: #667eea; text-decoration: none; font-weight: 600;">← Back to Home</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 BookHub. All rights reserved.</p>
</footer>

</body>
</html>
