<?php
// Include database connection
include 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header("Location: view_products.php");
    exit();
}

// Handle product deletion
try {
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        header("Location: view_products.php");
        exit();
    }
    
    // Delete product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    
    // Redirect back to view products with success message
    header("Location: view_products.php?deleted=1");
    exit();
    
} catch(PDOException $e) {
    // If error occurs, redirect with error message
    header("Location: view_products.php?error=1");
    exit();
}
?>
