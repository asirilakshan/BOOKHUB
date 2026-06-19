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

$sql = "SELECT id, fullname, email, DATE_FORMAT(created_at, '%Y-%m-%d') as joined FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
        <div style="margin-bottom: 40px;">
            <h1 style="font-size: 36px; color: #333;">👥 Manage Users</h1>
        </div>

        <div class="admin-container">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: left;">ID</th>
                        <th style="text-align: left;">👤 Name</th>
                        <th style="text-align: left;">📧 Email</th>
                        <th style="text-align: left;">📅 Joined</th>
                        <th style="text-align: center;">⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($result && $result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td><strong>" . htmlspecialchars($row['fullname']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['joined']) . "</td>";
                            echo "<td style='text-align: center;'>";
                            echo "<form method='POST' action='delete_user.php' style='display:inline;'>";
                            echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row['id']) . "'>";
                            echo "<button type='submit' class='delete-btn' onclick=\"return confirm('Are you sure?')\">🗑️ Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center; padding: 30px;'>📭 No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="dashbord.php" style="color: #667eea; text-decoration: none; font-weight: 600;">← Back to Dashboard</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 BookHub Admin. All rights reserved.</p>
</footer>

</body>
</html>
    } else {
        echo "<tr><td colspan='5'>No users found</td></tr>";
    }
    ?>
</table>
