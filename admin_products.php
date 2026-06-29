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
    $del_query = "DELETE FROM products WHERE id = $id";
    if (mysqli_query($conn, $del_query)) {
        $message = "Product successfully deleted.";
    }
    $action = 'list';
}

// Handle Add/Edit Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    
    if (empty($image_url)) $image_url = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=500&q=80'; // Default placeholder

    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        // Update
        $id = intval($_POST['product_id']);
        $sql = "UPDATE products SET name='$name', price=$price, category_id=$category_id, description='$description', image_url='$image_url' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $message = "Product successfully updated.";
        }
    } else {
        // Insert
        $sql = "INSERT INTO products (name, description, price, image_url, category_id) VALUES ('$name', '$description', $price, '$image_url', $category_id)";
        if (mysqli_query($conn, $sql)) {
            $message = "New product added to inventory.";
        }
    }
    $action = 'list';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Inventory - STAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container { padding: 120px 80px 50px 80px; max-width: 1200px; margin: 0 auto; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; background: rgba(20,20,20,0.8); }
        .table th, .table td { padding: 15px; text-align: left; border-bottom: 1px solid #333; }
        .table th { background: #111; font-family: 'Playfair Display', serif; font-size: 18px; }
        .action-links a { color: var(--accent-color); margin-right: 10px; text-decoration: none; }
        .action-links a.delete { color: #ff6b6b; }
        
        .form-container { background: rgba(20,20,20,0.8); padding: 30px; border: 1px solid #333; }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
            <h1 class="serif-text">Inventory Management</h1>
            <a href="admin_dashboard.php" style="color: #aaa;">&larr; Back to Dashboard</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($action == 'list'): ?>
            <a href="admin_products.php?action=add" class="btn-primary" style="padding: 10px 20px;">+ Add New Product</a>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>$" . number_format($row['price'], 2) . "</td>";
                        echo "<td class='action-links'>
                                <a href='admin_products.php?action=edit&id={$row['id']}'>Edit</a>
                                <a href='admin_products.php?action=delete&id={$row['id']}' class='delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

        <?php elseif ($action == 'add' || $action == 'edit'): 
            $edit_id = '';
            $p_name = ''; $p_price = ''; $p_desc = ''; $p_cat = 1; $p_img = '';
            
            if ($action == 'edit' && isset($_GET['id'])) {
                $edit_id = intval($_GET['id']);
                $res = mysqli_query($conn, "SELECT * FROM products WHERE id = $edit_id");
                if ($row = mysqli_fetch_assoc($res)) {
                    $p_name = $row['name'];
                    $p_price = $row['price'];
                    $p_desc = $row['description'];
                    $p_cat = $row['category_id'];
                    $p_img = $row['image_url'];
                }
            }
        ?>
            <div class="form-container">
                <h2 class="serif-text"><?php echo $action == 'add' ? 'Add Product' : 'Edit Product'; ?></h2>
                <form action="admin_products.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $edit_id; ?>">
                    
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($p_name); ?>" required>
                    </div>
                    
                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $p_price; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <?php
                                $cats = mysqli_query($conn, "SELECT * FROM categories");
                                while ($c = mysqli_fetch_assoc($cats)) {
                                    $sel = ($c['id'] == $p_cat) ? 'selected' : '';
                                    echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image_url" class="form-control" value="<?php echo htmlspecialchars($p_img); ?>" placeholder="Leave blank for placeholder">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($p_desc); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Save Product</button>
                    <a href="admin_products.php" style="margin-left: 20px; color: #aaa;">Cancel</a>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
