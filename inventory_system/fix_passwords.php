<?php
// Fix the user passwords
$host = '127.0.0.1';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=inventory_db", $username, $password);
    echo "✅ Connected to database<br>";
    
    // Create proper password hashes
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $user_password = password_hash('user123', PASSWORD_DEFAULT);
    
    echo "<h3>New Password Hashes:</h3>";
    echo "Admin password hash: $admin_password<br>";
    echo "User password hash: $user_password<br><br>";
    
    // Update admin password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$admin_password]);
    echo "✅ Admin password updated<br>";
    
    // Update user password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'user'");
    $stmt->execute([$user_password]);
    echo "✅ User password updated<br><br>";
    
    // Test the passwords
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
    
    echo "<br><h3>🎉 Passwords Fixed!</h3>";
    echo "Now you can login with:<br>";
    echo "<strong>Admin:</strong> username: admin, password: admin123<br>";
    echo "<strong>User:</strong> username: user, password: user123<br><br>";
    
    echo "<a href='index.php'>🔗 Go to Login Page</a>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>
