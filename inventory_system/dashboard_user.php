<?php
// Include database connection
include 'db.php';

// Check if user is logged in and is user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

// Handle search functionality
$search = '';
$supplier_filter = '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $supplier_filter = isset($_GET['supplier']) ? trim($_GET['supplier']) : '';
}

// Get products with search and filter
try {
    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND name LIKE ?";
        $params[] = "%$search%";
    }
    
    if (!empty($supplier_filter)) {
        $sql .= " AND supplier = ?";
        $params[] = $supplier_filter;
    }
    
    $sql .= " ORDER BY name ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    
    // Get unique suppliers for filter dropdown
    $stmt = $pdo->query("SELECT DISTINCT supplier FROM products ORDER BY supplier ASC");
    $suppliers = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error fetching products.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <h2>User Dashboard</h2>
            <ul class="nav-menu">
                <li><a href="dashboard_user.php" class="active">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- Welcome Message -->
        <div class="welcome">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
            <p>Inventory Viewing System</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <h3>Search Products</h3>
            <form method="GET" class="search-form">
                <div class="form-row">
                    <input type="text" name="search" placeholder="Search by product name..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    
                    <select name="supplier">
                        <option value="">All Suppliers</option>
                        <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?php echo htmlspecialchars($supplier['supplier']); ?>" 
                                <?php echo $supplier_filter == $supplier['supplier'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($supplier['supplier']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="submit" class="btn">Search</button>
                    <a href="dashboard_user.php" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="products-section">
            <h3>Products Inventory</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Supplier</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="6" class="no-results">No products found.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                            <tr class="<?php echo $product['quantity'] < 10 ? 'low-stock' : ''; ?>">
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($product['supplier']); ?></td>
                                <td>
                                    <?php if ($product['quantity'] < 10): ?>
                                        <span class="status low">Low Stock</span>
                                    <?php elseif ($product['quantity'] < 50): ?>
                                        <span class="status medium">Medium Stock</span>
                                    <?php else: ?>
                                        <span class="status high">In Stock</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Info -->
        <div class="summary-info">
            <p><strong>Total Products:</strong> <?php echo count($products); ?></p>
            <p><strong>Low Stock Items:</strong> 
                <?php 
                $low_count = 0;
                foreach ($products as $product) {
                    if ($product['quantity'] < 10) $low_count++;
                }
                echo $low_count;
                ?>
            </p>
        </div>
    </div>
</body>
</html>
