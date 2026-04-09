<?php
// Database setup script
$host = 'localhost';
$username = 'root';
$password = 'root';

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host", $username, $password);
    echo "✅ Connected to MySQL server successfully!<br>";
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS inventory_db");
    echo "✅ Database 'inventory_db' created/verified!<br>";
    
    // Select the database
    $pdo->exec("USE inventory_db");
    
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Users table created/verified!<br>";
    
    // Create products table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        quantity INT(11) NOT NULL DEFAULT 0,
        price DECIMAL(10,2) NOT NULL,
        supplier VARCHAR(100) NOT NULL,
        date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "✅ Products table created/verified!<br>";
    
    // Insert default users (check if they exist first)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $user_count = $stmt->fetch()['count'];
    
    if ($user_count == 0) {
        $pdo->exec("INSERT INTO users (username, password, role) VALUES 
            ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
            ('user', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user')");
        echo "✅ Default users inserted!<br>";
    } else {
        echo "ℹ️ Users already exist ($user_count users)<br>";
    }
    
    // Insert sample products (check if they exist first)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $product_count = $stmt->fetch()['count'];
    
    if ($product_count == 0) {
        $pdo->exec("INSERT INTO products (name, quantity, price, supplier) VALUES 
            ('Laptop Dell XPS', 15, 999.99, 'Dell Inc.'),
            ('Mouse Logitech', 45, 25.50, 'Logitech'),
            ('Keyboard Mechanical', 8, 89.99, 'Corsair'),
            ('Monitor 24 inch', 5, 199.99, 'Samsung'),
            ('USB Flash Drive 32GB', 120, 12.99, 'SanDisk')");
        echo "✅ Sample products inserted!<br>";
    } else {
        echo "ℹ️ Products already exist ($product_count products)<br>";
    }
    
    echo "<br><strong>🎉 Database setup complete!</strong><br>";
    echo "🔗 <a href='index.php'>Go to Login Page</a><br>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>
