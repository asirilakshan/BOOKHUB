<?php
include 'config/db.php';

$error = '';

if(isset($_POST['register'])){
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if(!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)){
        if($password !== $confirm_password){
            $error = "Passwords do not match";
        } else if(strlen($password) < 6){
            $error = "Password must be at least 6 characters";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if($stmt){
                $stmt->bind_param("sss", $name, $email, $password_hash);
                if($stmt->execute()){
                    $stmt->close();
                    header("Location: login.php?success=Registration successful. Please login.");
                    exit();
                } else {
                    if(strpos($stmt->error, 'Duplicate entry') !== false){
                        $error = "Email already registered";
                    } else {
                        $error = "Error: " . $stmt->error;
                    }
                }
                $stmt->close();
            }
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
    <title>Register - BookHub</title>
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
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<!-- Register Section -->
<section style="padding: 60px 8%; min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 450px;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-size: 36px; color: #333; margin-bottom: 10px;">Join BookHub! 📖</h1>
            <p style="color: #666; font-size: 16px;">Create your account to start renting and selling books</p>
        </div>

        <?php if(!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form class="auth-form" method="POST">
            <input type="text" name="name" placeholder="👤 Full Name" required>
            <input type="email" name="email" placeholder="📧 Email Address" required>
            <input type="password" name="password" placeholder="🔐 Password (min 6 chars)" required minlength="6">
            <input type="password" name="confirm_password" placeholder="🔐 Confirm Password" required minlength="6">
            <button type="submit" name="register">Create Account</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #666;">
            Already have an account? <a href="login.php" style="color: #667eea; font-weight: 600; text-decoration: none;">Login here</a>
        </p>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
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
