<?php
// Check different MySQL connection methods
echo "<h2>Testing MySQL Connections</h2>";

// Method 1: Try empty password
echo "<h3>Method 1: Empty Password</h3>";
try {
    $pdo = new PDO("mysql:host=localhost", "root", "");
    echo "✅ Connected with empty password<br>";
    
    // Check databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Databases: " . implode(', ', $databases) . "<br>";
    
} catch(PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "<br>";
}

// Method 2: Try 'root' password
echo "<h3>Method 2: Password 'root'</h3>";
try {
    $pdo = new PDO("mysql:host=localhost", "root", "root");
    echo "✅ Connected with password 'root'<br>";
    
    // Check databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Databases: " . implode(', ', $databases) . "<br>";
    
} catch(PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "<br>";
}

// Method 3: Try no password but specify charset
echo "<h3>Method 3: Empty Password + Charset</h3>";
try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");
    echo "✅ Connected with empty password + charset<br>";
    
    // Check databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Databases: " . implode(', ', $databases) . "<br>";
    
} catch(PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "<br>";
}

// Method 4: Try with 127.0.0.1 instead of localhost
echo "<h3>Method 4: 127.0.0.1 + Empty Password</h3>";
try {
    $pdo = new PDO("mysql:host=127.0.0.1", "root", "");
    echo "✅ Connected to 127.0.0.1 with empty password<br>";
    
    // Check databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Databases: " . implode(', ', $databases) . "<br>";
    
} catch(PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "<br>";
}

// Method 5: Try with 127.0.0.1 + root password
echo "<h3>Method 5: 127.0.0.1 + Password 'root'</h3>";
try {
    $pdo = new PDO("mysql:host=127.0.0.1", "root", "root");
    echo "✅ Connected to 127.0.0.1 with password 'root'<br>";
    
    // Check databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Databases: " . implode(', ', $databases) . "<br>";
    
} catch(PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "<br>";
}

echo "<h2>Recommendation</h2>";
echo "<p>Copy the working connection method above and update your db.php file accordingly.</p>";
echo "<p><a href='index.php'>Try Login Page</a></p>";
?>
