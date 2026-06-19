<?php
require_once __DIR__ . '/config/session.php';
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

$sql = "SELECT c.id, c.quantity, b.title, b.price, b.id as book_id 
        FROM cart c 
        JOIN books b ON c.book_id = b.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate totals
$grand_total = 0;
$items = [];
while($row = $result->fetch_assoc()){
    $items[] = $row;
    $grand_total += ($row['price'] * $row['quantity']);
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - BookHub</title>
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
            <li><a href="rent.php">🔄 Rent</a></li>
            <li><a href="sell.php">💸 Sell</a></li>
            <li><a href="profile.php">👤 Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<!-- Shopping Cart Section -->
<section style="padding: 60px 8%; min-height: calc(100vh - 200px);">
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="margin-bottom: 40px;">
            <h1 style="font-size: 42px; color: #333; margin-bottom: 10px;">🛒 Shopping Cart</h1>
            <p style="color: #666;">Manage your book collection</p>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <?php if(!empty($items)): ?>
            <div style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <th style="padding: 20px; text-align: left; font-weight: 600;">📚 Book Title</th>
                            <th style="padding: 20px; text-align: center; font-weight: 600;">💵 Price</th>
                            <th style="padding: 20px; text-align: center; font-weight: 600;">📦 Qty</th>
                            <th style="padding: 20px; text-align: center; font-weight: 600;">💰 Total</th>
                            <th style="padding: 20px; text-align: center; font-weight: 600;">⚙️ Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $row): ?>
                            <tr style="border-bottom: 1px solid #e0e0e0; transition: background 0.3s ease;">
                                <td style="padding: 20px; color: #333; font-weight: 500;"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td style="padding: 20px; text-align: center; color: #667eea; font-weight: 600;">$<?php echo htmlspecialchars($row['price']); ?></td>
                                <td style="padding: 20px; text-align: center; color: #333;"><?php echo htmlspecialchars($row['quantity']); ?></td>
                                <td style="padding: 20px; text-align: center; color: #333; font-weight: 600;">$<?php echo htmlspecialchars(number_format($row['price'] * $row['quantity'], 2)); ?></td>
                                <td style="padding: 20px; text-align: center;">
                                    <form method="POST" action="remove_from_cart.php" style="display: inline;">
                                        <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="submit" name="remove" style="padding: 8px 16px; background: #e74c3c; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                                            ❌ Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Cart Summary -->
                <div style="background: #f5f7fa; padding: 30px; border-top: 2px solid #e0e0e0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #333; font-size: 18px;">Subtotal:</h3>
                        <p style="color: #333; font-size: 20px; font-weight: 600;">$<?php echo number_format($grand_total, 2); ?></p>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid #e0e0e0;">
                        <h3 style="color: #333; font-size: 18px;">Shipping:</h3>
                        <p style="color: #667eea; font-size: 16px; font-weight: 600;">FREE 🎉</p>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h2 style="color: #333; font-size: 22px;">Grand Total:</h2>
                        <h2 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 28px;">$<?php echo number_format($grand_total, 2); ?></h2>
                    </div>
                </div>

                <!-- Checkout Button -->
                <div style="padding: 20px; text-align: right;">
                    <button style="padding: 16px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                        ✅ Proceed to Checkout
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div style="background: #f0f4ff; padding: 60px; border-radius: 15px; text-align: center;">
                <h3 style="color: #667eea; font-size: 24px; margin-bottom: 15px;">🛒 Your Cart is Empty</h3>
                <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
                    Start shopping and add your favorite books to the cart!
                </p>
                <a href="index.php" style="display: inline-block; padding: 14px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 10px; font-weight: 600; transition: all 0.3s ease;">
                    🏪 Continue Shopping
                </a>
            </div>
        <?php endif; ?>

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
