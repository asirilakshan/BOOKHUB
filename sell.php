<?php
require_once __DIR__ . '/config/session.php';
include 'config/db.php';

$error = '';
$success = '';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['submit'])){
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $price = $_POST['price'] ?? '';
    $type = $_POST['type'] ?? '';
    
    if(!empty($title) && !empty($author) && !empty($price) && !empty($type)){
        $sql = "INSERT INTO books (title, author, price, book_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if($stmt){
            $stmt->bind_param("ssds", $title, $author, $price, $type);
            if($stmt->execute()){
                $success = "Book listed successfully! 🎉";
                $stmt->close();
                header("Location: index.php?success=Book added successfully");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $error = "Please fill in all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Your Books - BookHub</title>
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
            <li><a href="profile.php">👤 Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<!-- Sell Section -->
<section style="padding: 60px 8%; min-height: calc(100vh - 200px);">
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-size: 42px; color: #333; margin-bottom: 15px;">💸 Sell Your Books</h1>
            <p style="color: #666; font-size: 16px; line-height: 1.8;">
                Have books you no longer need? List them on BookHub and earn money! 
                It's quick, easy, and free to list.
            </p>
        </div>

        <?php if(!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if(!empty($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form class="sell-form" method="POST">
            <div style="background: #f5f7fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h3 style="color: #667eea; margin-bottom: 10px;">📖 Book Information</h3>
                <input type="text" name="title" placeholder="📚 Book Title" required>
                <input type="text" name="author" placeholder="✍️ Author Name" required>
                <input type="number" name="price" placeholder="💵 Price ($)" step="0.01" min="0" required>
                
                <select name="type" required style="width: 100%; padding: 15px; margin-bottom: 20px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 15px; font-family: 'Poppins', sans-serif;">
                    <option value="">-- Select Type --</option>
                    <option value="sell">📖 Sell (Customer keeps book)</option>
                    <option value="rent">🔄 Rent (Customer returns book)</option>
                </select>
            </div>

            <button type="submit" name="submit" style="width: 100%; padding: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                📤 List Book Now
            </button>
        </form>

        <div style="background: #f0f4ff; padding: 25px; border-radius: 15px; margin-top: 30px; border-left: 5px solid #667eea;">
            <h3 style="color: #667eea; margin-bottom: 15px;">💡 Tips for Selling</h3>
            <ul style="color: #666; line-height: 2;">
                <li>✓ Use a clear, descriptive book title</li>
                <li>✓ Set a competitive price compared to similar listings</li>
                <li>✓ Choose the right category (Sell or Rent)</li>
                <li>✓ Ensure the book is in good condition before shipping</li>
                <li>✓ Respond quickly to buyer inquiries</li>
            </ul>
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
