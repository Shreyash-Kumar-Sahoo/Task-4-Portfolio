<?php
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // In a real application, you would generate a token and send an email here.
        // For this project, we just show a success message to simulate the flow.
        $message = "If an account with that email exists, a password reset link has been sent.";
    } else {
        $message = "Please enter a valid email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - STAR COLLECTIONS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="auth-container">
        <div class="auth-card">
            <h2 class="serif-text">Reset Password</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <p style="text-align: center; font-size: 14px; color: #bbb; margin-bottom: 25px;">Enter your email address and we will send you a link to reset your password.</p>

            <form action="forgot_password.php" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                </div>
                
                <button type="submit" class="btn-primary">Send Reset Link</button>
            </form>
            
            <div class="auth-links">
                <p style="margin-top: 15px;">Remembered your password? <a href="login.php">Log In Here</a></p>
            </div>
        </div>
    </div>

</body>
</html>
