<?php
// Check what's in the users table
$host = '127.0.0.1';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=inventory_db", $username, $password);
    echo "✅ Connected to database<br>";
    
    // Check if users table exists and has data
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll();
    
    echo "<h3>Users in database:</h3>";
    if (empty($users)) {
        echo "❌ No users found in database!<br>";
        echo "🔧 Let's create users...<br>";
        
        // Create users with simple passwords for testing
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        
        // Use simple password hashes for testing
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $user_password = password_hash('user123', PASSWORD_DEFAULT);
        
        $stmt->execute(['admin', $admin_password, 'admin']);
        $stmt->execute(['user', $user_password, 'user']);
        
        echo "✅ Users created successfully!<br>";
        
        // Check again
        $stmt = $pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll();
    }
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Password Hash</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
        echo "<td>" . htmlspecialchars($user['password']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Password Verification Test:</h3>";
    
    // Test admin login
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $admin_user = $stmt->fetch();
    
    if ($admin_user && password_verify('admin123', $admin_user['password'])) {
        echo "✅ Admin login test: PASSED<br>";
    } else {
        echo "❌ Admin login test: FAILED<br>";
    }
    
    // Test user login
    $stmt->execute(['user']);
    $user_user = $stmt->fetch();
    
    if ($user_user && password_verify('user123', $user_user['password'])) {
        echo "✅ User login test: PASSED<br>";
    } else {
        echo "❌ User login test: FAILED<br>";
    }
    
    echo "<br><h3>Try these login credentials:</h3>";
    echo "<strong>Admin:</strong> username: admin, password: admin123<br>";
    echo "<strong>User:</strong> username: user, password: user123<br>";
    
    echo "<br><a href='index.php'>🔗 Go to Login Page</a>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>
