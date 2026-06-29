<?php
session_start();
include('star_db.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Redirect Admin to Admin Dashboard
if ($_SESSION['role_id'] == 1) {
    header("location: admin_dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - STAR COLLECTIONS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            padding: 150px 80px 50px 80px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .dashboard-header {
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }
        .card {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid var(--border-color);
            padding: 30px;
            border-radius: 4px;
        }
        .card h3 {
            font-family: 'Playfair Display', serif;
            margin-top: 0;
            font-size: 24px;
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="serif-text">Welcome, <?php echo htmlspecialchars($username); ?>.</h1>
            <p style="color: #aaa;">This is your personal customer dashboard.</p>
        </div>

        <div class="dashboard-grid">
            <!-- Profile Info -->
            <div class="card">
                <h3>Account Details</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Status:</strong> Active Customer</p>
                <br>
                <a href="logout.php" class="btn-primary" style="padding: 10px 20px; font-size: 12px;">Sign Out</a>
            </div>

            <!-- Order History -->
            <div class="card">
                <h3>Order History</h3>
                <p style="color: #888;">You have not placed any orders yet. Head to the shop to find your new look.</p>
                <br>
                <a href="shop.php" class="btn-primary" style="padding: 10px 20px; font-size: 12px;">Browse Shop</a>
            </div>
        </div>
    </div>

</body>
</html>
