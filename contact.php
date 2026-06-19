<?php
include 'config/db.php';

$success = '';
$error = '';

if(isset($_POST['contact'])){
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if(!empty($name) && !empty($email) && !empty($message)){
        $sql = "INSERT INTO contacts (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        
        if($stmt){
            $stmt->bind_param("sss", $name, $email, $message);
            if($stmt->execute()){
                $success = "Message sent successfully. We'll get back to you soon. ✅";
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
    <title>Contact Us - BookHub</title>
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
            <li><a href="index.php#contact">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<!-- Contact Section -->
<section style="padding: 60px 8%; min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 600px;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-size: 42px; color: #333; margin-bottom: 15px;">📞 Get In Touch</h1>
            <p style="color: #666; font-size: 16px; line-height: 1.8;">
                Have questions or feedback? We'd love to hear from you! 
                Fill out the form below and we'll get back to you as soon as possible.
            </p>
        </div>

        <?php if(!empty($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if(!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form class="contact-form" method="POST">
            <input type="text" name="name" placeholder="👤 Your Name" required>
            <input type="email" name="email" placeholder="📧 Your Email" required>
            <textarea name="message" placeholder="💬 Your Message" rows="6" style="resize: none;" required></textarea>
            <button type="submit" name="contact">📤 Send Message</button>
        </form>

        <div style="background: #f0f4ff; padding: 25px; border-radius: 15px; margin-top: 30px; border-left: 5px solid #667eea;">
            <h3 style="color: #667eea; margin-bottom: 15px;">Other Ways to Reach Us</h3>
            <div style="color: #666; line-height: 2;">
                <p>📧 <strong>Email:</strong> support@bookhub.com</p>
                <p>📞 <strong>Phone:</strong> +1 (555) 123-4567</p>
                <p>🕐 <strong>Hours:</strong> Mon - Fri, 9:00 AM - 6:00 PM EST</p>
            </div>
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
