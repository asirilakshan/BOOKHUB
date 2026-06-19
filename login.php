<?php
require_once __DIR__ . '/config/session.php';
include 'config/db.php';

$error = '';

if(isset($_POST['login'])){
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if(!empty($email) && !empty($password)){
        $sql = "SELECT id, password, is_admin FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if($stmt){
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if($user && password_verify($password, $user['password'])){
                $_SESSION['user'] = $user['id'];
                if((int)$user['is_admin'] === 1){
                    $_SESSION['admin'] = $user['id'];
                } else {
                    unset($_SESSION['admin']);
                }
                $stmt->close();
                header((int)$user['is_admin'] === 1 ? "Location: adminpannel/dashbord.php" : "Location: index.php");
                exit();
            } else {
                $error = "Invalid email or password";
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
    <title>Login - BookHub</title>
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
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
</header>

<!-- Login Section -->
<section style="padding: 60px 8%; min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 450px;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-size: 36px; color: #333; margin-bottom: 10px;">Welcome Back! 👋</h1>
            <p style="color: #666; font-size: 16px;">Login to your BookHub account</p>
        </div>

        <?php if(!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if(isset($_GET['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <form class="auth-form" method="POST" action="login.php">
            <input type="email" name="email" placeholder="📧 Email Address" required>
            <input type="password" name="password" placeholder="🔐 Password" required>
            <button type="submit" name="login" value="1">Login to Account</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #666;">
            Don't have an account? <a href="register.php" style="color: #667eea; font-weight: 600; text-decoration: none;">Register here</a>
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
