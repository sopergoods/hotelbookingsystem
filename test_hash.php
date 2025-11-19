<?php
include_once 'Admin.php';

// Test username - CHANGE THIS to your actual admin username
$test_username = "admin";
$test_password = "password"; // CHANGE THIS to the password you're trying to use

echo "<h2>Admin Password Debug Tool</h2>";
echo "<hr>";

$adminObj = new Admin();
$admin = $adminObj->findByUsername($test_username);

if ($admin) {
    echo "✅ <strong>Admin found in database!</strong><br><br>";
    echo "<strong>Username:</strong> " . htmlspecialchars($admin['username']) . "<br>";
    echo "<strong>Admin ID:</strong> " . $admin['id'] . "<br>";
    echo "<strong>Password Hash in DB:</strong> " . htmlspecialchars($admin['password_hash']) . "<br>";
    echo "<strong>Hash Length:</strong> " . strlen($admin['password_hash']) . " characters<br><br>";
    
    echo "<hr>";
    echo "<h3>Password Verification Test</h3>";
    echo "<strong>Testing password:</strong> '" . htmlspecialchars($test_password) . "'<br><br>";
    
    $verify_result = password_verify($test_password, $admin['password_hash']);
    
    if ($verify_result) {
        echo "✅ <span style='color: green; font-size: 20px;'><strong>PASSWORD MATCHES!</strong></span><br>";
        echo "Login should work correctly.";
    } else {
        echo "❌ <span style='color: red; font-size: 20px;'><strong>PASSWORD DOES NOT MATCH!</strong></span><br><br>";
        echo "<strong>Possible issues:</strong><br>";
        echo "1. The password in database is not properly hashed<br>";
        echo "2. You're using the wrong password<br>";
        echo "3. The hash was created incorrectly<br><br>";
        
        echo "<hr>";
        echo "<h3>Generate New Hash</h3>";
        $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
        echo "<strong>New hash for password '" . htmlspecialchars($test_password) . "':</strong><br>";
        echo "<textarea style='width: 100%; height: 80px; font-family: monospace;'>" . $new_hash . "</textarea><br><br>";
        
        echo "<strong>Run this SQL to update your admin password:</strong><br>";
        echo "<textarea style='width: 100%; height: 100px; font-family: monospace;'>";
        echo "UPDATE admin_users SET password_hash = '" . $new_hash . "' WHERE username = '" . $test_username . "';";
        echo "</textarea><br>";
        echo "<small>Copy and run this in your database, then try logging in again.</small>";
    }
} else {
    echo "❌ <strong style='color: red;'>No admin found with username: '" . htmlspecialchars($test_username) . "'</strong><br><br>";
    echo "<strong>Possible issues:</strong><br>";
    echo "1. The username is wrong<br>";
    echo "2. No admin account exists in the database<br>";
    echo "3. Check your database table 'admin_users'<br><br>";
    
    echo "<hr>";
    echo "<h3>Create New Admin Account</h3>";
    $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
    echo "<strong>SQL to create admin account:</strong><br>";
    echo "<textarea style='width: 100%; height: 120px; font-family: monospace;'>";
    echo "INSERT INTO admin_users (username, password_hash) VALUES ('" . $test_username . "', '" . $new_hash . "');";
    echo "</textarea><br>";
    echo "<small>Copy and run this in your database to create the admin account.</small>";
}
?>