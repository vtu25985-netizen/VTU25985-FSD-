<?php
// Include database connection
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get all products
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
    $products = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error fetching products.";
}

// Determine user role for edit/delete buttons
$is_admin = ($_SESSION['role'] == 'admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products - Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <h2>View Products</h2>
            <ul class="nav-menu">
                <?php if ($is_admin): ?>
                    <li><a href="dashboard_admin.php">Dashboard</a></li>
                    <li><a href="add_product.php">Add Product</a></li>
                    <li><a href="view_products.php" class="active">View Products</a></li>
                <?php else: ?>
                    <li><a href="dashboard_user.php" class="active">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="products-header">
            <h3>All Products</h3>
            <?php if ($is_admin): ?>
                <a href="add_product.php" class="btn btn-primary">Add New Product</a>
            <?php endif; ?>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

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
                        <?php if ($is_admin): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="<?php echo $is_admin ? '7' : '6'; ?>" class="no-results">
                            No products found.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr class="<?php echo $product['quantity'] < 10 ? 'low-stock' : ''; ?>">
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['supplier']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($product['date_added'])); ?></td>
                            <?php if ($is_admin): ?>
                                <td class="actions">
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" 
                                       class="btn btn-sm btn-edit">Edit</a>
                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" 
                                       class="btn btn-sm btn-delete" 
                                       onclick="return confirm('Are you sure you want to delete this product?');">
                                        Delete
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary Statistics -->
        <div class="summary-stats">
            <div class="stat-item">
                <strong>Total Products:</strong> <?php echo count($products); ?>
            </div>
            <div class="stat-item">
                <strong>Low Stock Items:</strong> 
                <?php 
                $low_count = 0;
                $total_value = 0;
                foreach ($products as $product) {
                    if ($product['quantity'] < 10) $low_count++;
                    $total_value += $product['quantity'] * $product['price'];
                }
                echo $low_count;
                ?>
            </div>
            <div class="stat-item">
                <strong>Total Inventory Value:</strong> $<?php echo number_format($total_value, 2); ?>
            </div>
        </div>
    </div>
</body>
</html>
