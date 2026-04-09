<?php
// Include database connection
include 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Handle form submission
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $name = trim($_POST['name']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $supplier = trim($_POST['supplier']);
    
    // Validate inputs
    if (empty($name) || empty($supplier)) {
        $error = "Product name and supplier are required.";
    } elseif ($quantity < 0) {
        $error = "Quantity cannot be negative.";
    } elseif ($price < 0) {
        $error = "Price cannot be negative.";
    } else {
        try {
            // Insert product into database
            $stmt = $pdo->prepare("INSERT INTO products (name, quantity, price, supplier) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $quantity, $price, $supplier]);
            
            $success = "Product added successfully!";
            
            // Clear form
            $_POST = [];
            
        } catch(PDOException $e) {
            $error = "Error adding product: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <h2>Add Product</h2>
            <ul class="nav-menu">
                <li><a href="dashboard_admin.php">Dashboard</a></li>
                <li><a href="add_product.php" class="active">Add Product</a></li>
                <li><a href="view_products.php">View Products</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h3>Add New Product</h3>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="product-form">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="0" required 
                           value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="price">Price ($):</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" required 
                           value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="supplier">Supplier:</label>
                    <input type="text" id="supplier" name="supplier" required 
                           value="<?php echo isset($_POST['supplier']) ? htmlspecialchars($_POST['supplier']) : ''; ?>">
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                    <a href="dashboard_admin.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
