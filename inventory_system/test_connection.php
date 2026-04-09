<?php
// Test database connection without specifying database
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server first
    $pdo = new PDO("mysql:host=$host", $username, $password);
    echo "✅ Connected to MySQL server successfully!<br>";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'inventory_db'");
    $db_exists = $stmt->rowCount() > 0;
    
    if ($db_exists) {
        echo "✅ Database 'inventory_db' exists!<br>";
        
        // Try to connect to the database
        $pdo = new PDO("mysql:host=$host;dbname=inventory_db", $username, $password);
        echo "✅ Connected to 'inventory_db' database successfully!<br>";
        
        // Check if tables exist
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "📋 Tables found: " . implode(', ', $tables) . "<br>";
        
        if (in_array('users', $tables) && in_array('products', $tables)) {
            echo "✅ All required tables exist!<br>";
            
            // Check if users table has data
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
            $user_count = $stmt->fetch()['count'];
            echo "👥 Users in database: $user_count<br>";
            
            // Check if products table has data
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
            $product_count = $stmt->fetch()['count'];
            echo "📦 Products in database: $product_count<br>";
            
        } else {
            echo "❌ Required tables missing. Need to create tables.<br>";
        }
    } else {
        echo "❌ Database 'inventory_db' does not exist. Need to create database.<br>";
    }
    
} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "<br>";
    
    // Try with common passwords
    $passwords = ['root', 'password', 'admin', ''];
    foreach ($passwords as $pwd) {
        try {
            $pdo = new PDO("mysql:host=$host", $username, $pwd);
            echo "✅ Connected with password: '$pwd'<br>";
            echo "💡 Update your db.php file to use password: '$pwd'<br>";
            break;
        } catch(PDOException $e) {
            echo "❌ Failed with password: '$pwd'<br>";
        }
    }
}
?>
