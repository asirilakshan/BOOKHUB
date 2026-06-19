<?php
require_once __DIR__ . '/../config/session.php';
require '../config/db.php';

if (!$conn) {
    die('Database connection failed');
}

// Check if user is admin
if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit();
}

// Get statistics
$books_result = $conn->query("SELECT COUNT(*) as count FROM books");
$books_count = $books_result ? $books_result->fetch_assoc()['count'] : 0;

$users_result = $conn->query("SELECT COUNT(*) as count FROM users");
$users_count = $users_result ? $users_result->fetch_assoc()['count'] : 0;

$orders_result = $conn->query("SELECT COUNT(*) as count FROM cart");
$orders_count = $orders_result ? $orders_result->fetch_assoc()['count'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BookHub</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<header>
    <nav class="navbar">
        <a href="../index.php" style="text-decoration: none;"><div class="logo">📚 BookHub Admin</div></a>
        <ul class="nav-links" style="gap: 20px;">
            <li><a href="dashbord.php">📊 Dashboard</a></li>
            <li><a href="books.php">📖 Books</a></li>
            <li><a href="users.php">👥 Users</a></li>
            <li><a href="orders.php">📦 Orders</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<!-- Admin Section -->
<section style="padding: 60px 8%;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 50px;">
            <h1 style="font-size: 42px; color: #333; margin-bottom: 10px;">📊 Admin Dashboard</h1>
            <p style="color: #666;">Welcome to the BookHub administration panel</p>
        </div>

        <!-- Stats Cards -->
        <div class="dashboard-stats" style="margin-bottom: 50px;">
            <div class="stat-box">
                <div style="font-size: 40px; margin-bottom: 15px;">📚</div>
                <h3 style="margin-bottom: 10px;">Total Books</h3>
                <p style="font-size: 48px; margin: 0;"><?php echo htmlspecialchars($books_count); ?></p>
            </div>
            
            <div class="stat-box">
                <div style="font-size: 40px; margin-bottom: 15px;">👥</div>
                <h3 style="margin-bottom: 10px;">Total Users</h3>
                <p style="font-size: 48px; margin: 0;"><?php echo htmlspecialchars($users_count); ?></p>
            </div>
            
            <div class="stat-box">
                <div style="font-size: 40px; margin-bottom: 15px;">📦</div>
                <h3 style="margin-bottom: 10px;">Total Orders</h3>
                <p style="font-size: 48px; margin: 0;"><?php echo htmlspecialchars($orders_count); ?></p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); padding: 30px;">
            <h2 style="color: #333; font-size: 24px; margin-bottom: 25px; border-bottom: 3px solid #667eea; padding-bottom: 15px;">
                ⚙️ Quick Actions
            </h2>

            <div class="dashboard-links">
                <a href="books.php">
                    <div style="font-size: 32px; margin-bottom: 10px;">📖</div>
                    <h3>Manage Books</h3>
                    <p>View and manage all books</p>
                </a>

                <a href="users.php">
                    <div style="font-size: 32px; margin-bottom: 10px;">👥</div>
                    <h3>Manage Users</h3>
                    <p>View and manage user accounts</p>
                </a>

                <a href="orders.php">
                    <div style="font-size: 32px; margin-bottom: 10px;">📦</div>
                    <h3>View Orders</h3>
                    <p>Manage customer orders</p>
                </a>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="../index.php" style="color: #667eea; text-decoration: none; font-weight: 600;">← Back to Website</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 BookHub Admin. All rights reserved.</p>
</footer>

</body>
</html>
