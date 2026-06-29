<?php
session_start();
include('star_db.php');

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $query = "SELECT id, username, password_hash, role_id FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role_id);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        session_regenerate_id();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['user_id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['role_id'] = $role_id;
                        
                        // Redirect user to dashboard
                        header("location: dashboard.php");
                        exit;
                    } else {
                        $error = "Invalid password.";
                    }
                }
            } else {
                $error = "No account found with that email.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - STAR COLLECTIONS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="auth-container">
        <div class="auth-card">
            <h2 class="serif-text">Welcome Back</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn-primary">Log In</button>
            </form>
            
            <div class="auth-links">
                <p><a href="forgot_password.php">Forgot your password?</a></p>
                <p style="margin-top: 15px;">Don't have an account? <a href="register.php">Register Here</a></p>
            </div>
        </div>
    </div>

</body>
</html>
