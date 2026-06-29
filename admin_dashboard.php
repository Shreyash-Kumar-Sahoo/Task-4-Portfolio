<?php
session_start();
include('star_db.php');

// Check if user is logged in AND is an Admin (role_id = 1)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role_id'] != 1) {
    header("location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch real statistics from database
$user_count_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role_id = 2");
$total_customers = mysqli_fetch_assoc($user_count_res)['count'];

$product_count_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
$total_products = mysqli_fetch_assoc($product_count_res)['count'];

// Mock order data for analytics chart (since the store isn't live yet)
$total_orders = 142;
$total_revenue = 12450.00;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard & Analytics - STAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Include Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .admin-container { padding: 120px 80px 50px 80px; max-width: 1200px; margin: 0 auto; }
        .admin-header { margin-bottom: 40px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px; }
        
        /* Stats row */
        .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-box { background: rgba(30, 20, 20, 0.8); border: 1px solid #522; padding: 25px; border-radius: 4px; text-align: center; }
        .stat-box h4 { margin: 0 0 10px 0; color: #aaa; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .stat-box .number { font-size: 36px; font-family: 'Playfair Display', serif; color: var(--accent-color); }
        
        /* Charts row */
        .charts-row { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 50px; }
        .chart-container { background: rgba(20, 20, 20, 0.8); border: 1px solid #333; padding: 25px; border-radius: 4px; }
        .chart-container h3 { font-family: 'Playfair Display', serif; margin-top: 0; margin-bottom: 20px; }
        
        /* Modules row */
        .admin-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; }
        .admin-card { background: rgba(20, 20, 20, 0.8); border: 1px solid #333; padding: 30px; border-radius: 4px; text-align: center; transition: transform 0.3s; }
        .admin-card:hover { transform: translateY(-5px); border-color: var(--accent-color); }
        .admin-card h3 { font-family: 'Playfair Display', serif; font-size: 24px; margin-bottom: 15px; }
        .admin-card p { color: #ccc; font-size: 14px; margin-bottom: 25px; }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="serif-text">Command Center</h1>
            <p style="color: #aaa;">Logged in securely as Super Admin (<?php echo htmlspecialchars($username); ?>)</p>
        </div>

        <!-- 1. Dashboard with Statistics -->
        <div class="stats-row">
            <div class="stat-box">
                <h4>Total Revenue</h4>
                <div class="number">$<?php echo number_format($total_revenue); ?></div>
            </div>
            <div class="stat-box">
                <h4>Active Orders</h4>
                <div class="number"><?php echo $total_orders; ?></div>
            </div>
            <div class="stat-box">
                <h4>Registered Customers</h4>
                <div class="number"><?php echo $total_customers; ?></div>
            </div>
            <div class="stat-box">
                <h4>Total Products</h4>
                <div class="number"><?php echo $total_products; ?></div>
            </div>
        </div>

        <!-- 3. View Analytics (Orders per day, Active Users) -->
        <div class="charts-row">
            <div class="chart-container">
                <h3>Revenue & Orders Overview (Last 7 Days)</h3>
                <canvas id="ordersChart" height="100"></canvas>
            </div>
            <div class="chart-container">
                <h3>User Demographics</h3>
                <canvas id="usersChart" height="200"></canvas>
            </div>
        </div>

        <!-- 2. Manage Users & Records (Modules) -->
        <h2 class="serif-text" style="border-bottom: 1px solid #333; padding-bottom: 15px; margin-bottom: 30px;">Management Modules</h2>
        <div class="admin-grid">
            <div class="admin-card">
                <h3>Manage Products</h3>
                <p>Add, edit, or delete items from the STAR storefront catalog. Control inventory levels.</p>
                <a href="admin_products.php" class="btn-primary" style="padding: 10px 20px; font-size: 12px; width: 200px;">Manage Inventory</a>
            </div>
            
            <div class="admin-card">
                <h3>Manage Users & Records</h3>
                <p>View registered customers, monitor activity, and remove malicious accounts.</p>
                <a href="admin_users.php" class="btn-primary" style="padding: 10px 20px; font-size: 12px; width: 200px;">User Management</a>
            </div>
        </div>
    </div>

    <!-- Chart.js Initialization -->
    <script>
        // Line Chart for Orders/Revenue
        const ctxOrders = document.getElementById('ordersChart').getContext('2d');
        new Chart(ctxOrders, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Daily Orders',
                    data: [12, 19, 15, 25, 22, 30, 19],
                    borderColor: '#e8e2d3', // Brand accent color
                    backgroundColor: 'rgba(232, 226, 211, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, grid: { color: '#333' }, ticks: { color: '#aaa' } },
                    x: { grid: { color: '#333' }, ticks: { color: '#aaa' } }
                },
                plugins: {
                    legend: { labels: { color: '#fff' } }
                }
            }
        });

        // Doughnut Chart for Users
        const ctxUsers = document.getElementById('usersChart').getContext('2d');
        new Chart(ctxUsers, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Inactive', 'New'],
                datasets: [{
                    data: [<?php echo $total_customers; ?>, 12, 5],
                    backgroundColor: ['#e8e2d3', '#333333', '#888888'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#fff' } }
                }
            }
        });
    </script>
</body>
</html>
