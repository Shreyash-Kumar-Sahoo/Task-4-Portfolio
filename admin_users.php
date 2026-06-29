<?php
session_start();
include('star_db.php');

// Security check: only Admins
if (!isset($_SESSION['loggedin']) || $_SESSION['role_id'] != 1) {
    header("location: login.php");
    exit;
}

$action = $_GET['action'] ?? 'list';
$message = '';

// Handle Deletion
if ($action == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Prevent admin from deleting themselves
    if ($id == $_SESSION['user_id']) {
        $message = "Error: You cannot delete your own admin account.";
    } else {
        $del_query = "DELETE FROM users WHERE id = $id";
        if (mysqli_query($conn, $del_query)) {
            $message = "User record successfully deleted.";
        }
    }
    $action = 'list';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - STAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container { padding: 120px 80px 50px 80px; max-width: 1200px; margin: 0 auto; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; background: rgba(20,20,20,0.8); }
        .table th, .table td { padding: 15px; text-align: left; border-bottom: 1px solid #333; }
        .table th { background: #111; font-family: 'Playfair Display', serif; font-size: 18px; }
        .action-links a { color: #ff6b6b; text-decoration: none; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        .action-links a:hover { text-decoration: underline; }
        .badge-admin { background: #522; color: #ff9999; padding: 4px 8px; border-radius: 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        .badge-customer { background: #333; color: #aaa; padding: 4px 8px; border-radius: 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
            <h1 class="serif-text">User Records Management</h1>
            <a href="admin_dashboard.php" style="color: #aaa; text-decoration: none;">&larr; Back to Dashboard</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Account Type</th>
                    <th>Registered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch users joined with roles
                $sql = "SELECT users.*, roles.role_name FROM users JOIN roles ON users.role_id = roles.id ORDER BY users.id DESC";
                $res = mysqli_query($conn, $sql);
                
                while ($row = mysqli_fetch_assoc($res)) {
                    $badge_class = ($row['role_id'] == 1) ? 'badge-admin' : 'badge-customer';
                    $date = date("M j, Y", strtotime($row['created_at']));
                    
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td><strong>" . htmlspecialchars($row['username']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td><span class='$badge_class'>" . htmlspecialchars($row['role_name']) . "</span></td>";
                    echo "<td>{$date}</td>";
                    echo "<td class='action-links'>";
                    if ($row['id'] != $_SESSION['user_id']) {
                        echo "<a href='admin_users.php?action=delete&id={$row['id']}' onclick='return confirm(\"Permanently delete this user record?\")'>Remove</a>";
                    } else {
                        echo "<span style='color: #666; font-size: 12px;'>(You)</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
