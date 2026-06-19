<?php
require_once __DIR__ . '/config/session.php';
include 'config/db.php';

$success = '';
$error = $_GET['error'] ?? '';

// Handle add to cart (buy)
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
                $stmt->close();
                header("Location: shopingcart.php?success=Book added to cart");
                exit();
            } else {
                $error = "Error adding to cart: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Fetch books from database
$sql = "SELECT * FROM books ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books - BookHub</title>
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
            <li><a href="browse_books.php">📚 Browse</a></li>
            <li><a href="rent.php">🔄 Rent</a></li>
            <li><a href="sell.php">💸 Sell</a></li>
            <?php if(isset($_SESSION['user'])): ?>
                <li><a href="shopingcart.php">🛒 Cart</a></li>
                <li><a href="profile.php">👤 Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Browse Books Section -->
<section style="padding: 60px 8%; min-height: calc(100vh - 200px);">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-size: 42px; color: #333; margin-bottom: 15px;">📚 Browse All Books</h1>
            <p style="color: #666; font-size: 16px;">Find your next favorite book to buy or rent</p>
        </div>

        <form class="api-search-form" id="browseApiSearchForm" style="margin-bottom: 16px;">
            <input type="search" id="browseApiSearchInput" placeholder="Search free online books by title, author, or ISBN" value="popular books" required>
            <button type="submit">Search Books</button>
        </form>

        <div class="api-search-status" id="browseApiSearchStatus">
            Loading books from Open Library...
        </div>

        <div class="book-grid api-results-grid" id="browseApiResults" style="margin-bottom: 50px;"></div>

        <?php if(!empty($success)): ?>
            <div class="success-message" style="margin-bottom: 30px;"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if(!empty($error)): ?>
            <div class="error-message" style="margin-bottom: 30px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_GET['added'])): ?>
            <div class="success-message" style="margin-bottom: 30px;">✅ Book added to cart! <a href="shopingcart.php" style="color: white; font-weight: bold;">View Cart</a></div>
        <?php endif; ?>
        
        <?php if($result && $result->num_rows > 0): ?>
            <h2 style="font-size: 28px; color: #333; margin: 30px 0;">Books From BookHub Store</h2>
            <div class="book-grid">
                <?php while($row = $result->fetch_assoc()): 
                    $book_id = htmlspecialchars($row['id']);
                    $title = htmlspecialchars($row['title']);
                    $author = htmlspecialchars($row['author']);
                    $price = htmlspecialchars($row['price']);
                    $type = htmlspecialchars($row['book_type']);
                ?>
                    <div class="book-card">
                        <img src="https://covers.openlibrary.org/b/id/<?php echo rand(1000000, 9999999); ?>-M.jpg" alt="<?php echo $title; ?>" style="height: 300px; object-fit: cover;">
                        <h3><?php echo $title; ?></h3>
                        <p style="color: #666; margin-bottom: 8px;">by <?php echo $author; ?></p>
                        <p style="color: #667eea; font-weight: 600; font-size: 18px; margin-bottom: 15px;">
                            <?php echo $type === 'rent' ? '🔄 Rent: $' : '📖 Buy: $'; ?><?php echo $price; ?><?php echo $type === 'rent' ? '/week' : ''; ?>
                        </p>
                        
                        <?php if($type === 'sell'): ?>
                            <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                                <button type="submit" name="add_to_cart" style="padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                                    🛒 Add to Cart
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="rent.php" style="display: block; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; text-align: center; text-decoration: none;">
                                🔄 Rent Now
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div style="background: #f0f4ff; padding: 60px; border-radius: 15px; text-align: center;">
                <h3 style="color: #667eea; font-size: 24px; margin-bottom: 15px;">📚 No Store Books Yet</h3>
                <p style="color: #666;">You can still browse free Open Library results above.</p>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 50px;">
            <a href="index.php" style="color: #667eea; text-decoration: none; font-weight: 600;">← Back to Home</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 BookHub. All rights reserved.</p>
</footer>

<script src="js/browse_books_api.js"></script>
</body>
</html>
