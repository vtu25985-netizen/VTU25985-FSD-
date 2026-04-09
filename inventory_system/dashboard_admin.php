<?php
// Include database connection
include 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Get dashboard statistics
try {
    // Total products
    $stmt = $pdo->query("SELECT COUNT(*) as total_products FROM products");
    $total_products = $stmt->fetch()['total_products'];
    
    // Total stock quantity
    $stmt = $pdo->query("SELECT SUM(quantity) as total_quantity FROM products");
    $total_quantity = $stmt->fetch()['total_quantity'];
    
    // Low stock count (quantity < 10)
    $stmt = $pdo->query("SELECT COUNT(*) as low_stock FROM products WHERE quantity < 10");
    $low_stock = $stmt->fetch()['low_stock'];
    
    // Recent products
    $stmt = $pdo->query("SELECT * FROM products ORDER BY date_added DESC LIMIT 5");
    $recent_products = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error fetching dashboard data.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <h2>Admin Dashboard</h2>
            <ul class="nav-menu">
                <li><a href="dashboard_admin.php" class="active">Dashboard</a></li>
                <li><a href="add_product.php">Add Product</a></li>
                <li><a href="view_products.php">View Products</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- Welcome Message -->
        <div class="welcome">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
            <p>Admin Panel - Inventory Management System</p>
        </div>

        <!-- Dashboard Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Products</h3>
                <p class="stat-number"><?php echo $total_products; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Total Stock Quantity</h3>
                <p class="stat-number"><?php echo $total_quantity; ?></p>
            </div>
            
            <div class="stat-card warning">
                <h3>Low Stock Items</h3>
                <p class="stat-number"><?php echo $low_stock; ?></p>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="recent-products">
            <h3>Recent Products</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Supplier</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_products as $product): ?>
                        <tr class="<?php echo $product['quantity'] < 10 ? 'low-stock' : ''; ?>">
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['supplier']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($product['date_added'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-buttons">
                <a href="add_product.php" class="btn btn-primary">Add New Product</a>
                <a href="view_products.php" class="btn btn-secondary">View All Products</a>
            </div>
        </div>
    </div>
</body>
</html>
