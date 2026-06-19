<?php
require_once __DIR__ . '/config/session.php';
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

if(isset($_POST['rent_book'])){
    $book_id = $_POST['book_id'] ?? '';
    $rental_days = $_POST['rental_days'] ?? '';
    
    if(!empty($book_id) && !empty($rental_days)){
        $cart_sql = "INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, 1)
                     ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $cart_stmt = $conn->prepare($cart_sql);

        if($cart_stmt){
            $cart_stmt->bind_param("ii", $user_id, $book_id);
            if(!$cart_stmt->execute()){
                $error = "Error adding rental to cart: " . $cart_stmt->error;
            }
            $cart_stmt->close();
        }

        $sql = "INSERT INTO rentals (user_id, book_id, rental_days, rental_date, due_date) 
                VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))";
        $stmt = $conn->prepare($sql);
        
        if($stmt){
            $stmt->bind_param("iiii", $user_id, $book_id, $rental_days, $rental_days);
            if($stmt->execute()){
                $stmt->close();
                header("Location: shopingcart.php?success=Rental book added to cart");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $error = "Please select a book and rental duration";
    }
}

// Fetch available books for rent
$sql = "SELECT * FROM books WHERE book_type = 'rent'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Books - BookHub</title>
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

<!-- Rent Books Section -->
<section style="padding: 60px 8%; min-height: calc(100vh - 200px);">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-size: 42px; color: #333; margin-bottom: 15px;">🔄 Rent Books</h1>
            <p style="color: #666; font-size: 16px; line-height: 1.8;">
                Browse our collection of books available for rent. Save money while enjoying your favorite reads!
            </p>
        </div>

        <?php if(!empty($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if(!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form class="api-search-form" id="rentApiSearchForm" style="margin-bottom: 16px;">
            <input type="search" id="rentApiSearchInput" placeholder="Search online books to rent by title, author, or ISBN" value="student books" required>
            <button type="submit">Search Rental Books</button>
        </form>

        <div class="api-search-status" id="rentApiSearchStatus">
            Loading rental books from Open Library...
        </div>

        <div class="book-grid api-results-grid" id="rentApiResults" style="margin-bottom: 50px;"></div>
        
        <?php if($result && $result->num_rows > 0): ?>
            <h2 style="font-size: 28px; color: #333; margin: 30px 0;">BookHub Rental Books</h2>
            <div class="book-grid">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <img src="https://covers.openlibrary.org/b/id/<?php echo rand(1000000, 9999999); ?>-M.jpg" alt="<?php echo htmlspecialchars($row['title']); ?>" style="height: 300px; object-fit: cover;">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p style="color: #666; margin-bottom: 8px;">by <?php echo htmlspecialchars($row['author']); ?></p>
                        <p style="color: #667eea; font-weight: 600; font-size: 18px; margin-bottom: 15px;">
                            💰 $<?php echo htmlspecialchars($row['price']); ?>/day
                        </p>
                        
                        <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <select name="rental_days" required style="padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; font-family: 'Poppins', sans-serif;">
                                <option value="">📅 Select Duration</option>
                                <option value="1">1 Day - $<?php echo htmlspecialchars($row['price']); ?></option>
                                <option value="7">7 Days - $<?php echo number_format($row['price'] * 7, 2); ?></option>
                                <option value="14">14 Days - $<?php echo number_format($row['price'] * 14, 2); ?></option>
                                <option value="30">30 Days - $<?php echo number_format($row['price'] * 30, 2); ?></option>
                            </select>
                            <button type="submit" name="rent_book" style="padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                                🛒 Rent Now
                            </button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div style="background: #f0f4ff; padding: 40px; border-radius: 15px; text-align: center; margin: 40px 0;">
                <h3 style="color: #667eea; font-size: 24px; margin-bottom: 10px;">📚 No Local Rental Books Yet</h3>
                <p style="color: #666;">You can still browse online rental-style results above.</p>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 50px;">
            <a href="index.php" style="color: #667eea; text-decoration: none; font-weight: 600; font-size: 16px;">← Back to Home</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 BookHub. All rights reserved.</p>
</footer>

<script src="js/rent_books_api.js"></script>
</body>
</html>
