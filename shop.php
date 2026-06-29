<?php
session_start();
include('star_db.php');

// Searching and Filtering
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Pagination
$limit = 8; // products per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Build Query
$where_clause = "WHERE 1=1";
if (!empty($search)) {
    $where_clause .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}
if ($category_filter > 0) {
    $where_clause .= " AND category_id = $category_filter";
}

// Count total for pagination
$count_query = "SELECT COUNT(*) as total FROM products $where_clause";
$count_res = mysqli_query($conn, $count_query);
$total_rows = mysqli_fetch_assoc($count_res)['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch products
$sql = "SELECT * FROM products $where_clause ORDER BY id DESC LIMIT $limit OFFSET $offset";
$products = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - STAR COLLECTIONS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .shop-container { padding: 120px 80px 50px 80px; max-width: 1400px; margin: 0 auto; }
        
        .toolbar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid #333;
        }
        
        .search-bar { display: flex; gap: 10px; }
        .search-bar input { width: 300px; padding: 10px 15px; }
        .search-bar select { padding: 10px; }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
        
        .product-card {
            background: rgba(20,20,20,0.5); border: 1px solid #222;
            transition: transform 0.3s;
        }
        .product-card:hover { transform: translateY(-5px); border-color: var(--accent-color); }
        .product-img { width: 100%; height: 350px; object-fit: cover; }
        .product-info { padding: 20px; }
        .product-title { font-family: 'Playfair Display', serif; font-size: 18px; margin: 0 0 10px 0; }
        .product-price { font-size: 14px; color: var(--accent-color); margin-bottom: 15px; }
        
        .pagination { display: flex; justify-content: center; gap: 10px; margin-top: 50px; }
        .page-link {
            padding: 8px 16px; border: 1px solid #333; color: #fff; text-decoration: none;
            transition: background 0.3s;
        }
        .page-link:hover, .page-link.active { background: var(--accent-color); color: #000; }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="shop-container">
        
        <div class="toolbar">
            <h1 class="serif-text">The Collection</h1>
            
            <form action="shop.php" method="GET" class="search-bar">
                <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                
                <select name="category" class="form-control" onchange="this.form.submit()">
                    <option value="0">All Categories</option>
                    <?php
                    $cats = mysqli_query($conn, "SELECT * FROM categories");
                    while ($c = mysqli_fetch_assoc($cats)) {
                        $sel = ($c['id'] == $category_filter) ? 'selected' : '';
                        echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 12px;">Filter</button>
            </form>
        </div>

        <div class="product-grid">
            <?php if (mysqli_num_rows($products) > 0): ?>
                <?php while($p = mysqli_fetch_assoc($products)): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="product-img">
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                            <div class="product-price">$<?php echo number_format($p['price'], 2); ?></div>
                            <a href="#" class="btn-primary" style="padding: 10px 15px; font-size: 11px; width: 100%; box-sizing: border-box;">Add to Cart</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; color: #888; padding: 50px;">No products found matching your criteria.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for($i=1; $i<=$total_pages; $i++): ?>
                    <a href="shop.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>" 
                       class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
