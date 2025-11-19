<?php
include_once 'User.php';
include_once 'user_auth.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// ADD THIS DEBUG:
echo "<pre style='background: lightblue; padding: 20px;'>";
echo "Login Password Debug:\n";
echo "Length: " . strlen($password) . "\n";
echo "Visible: '" . htmlspecialchars($password) . "'\n";
echo "Hex: " . bin2hex($password) . "\n";
echo "</pre>";

    echo "<div style='background: #f0f0f0; padding: 20px; margin: 20px;'>";
    echo "<h3>DEBUG INFO:</h3>";
    echo "Email entered: " . htmlspecialchars($email) . "<br>";
    echo "Password entered: " . htmlspecialchars($password) . "<br><br>";

    if (empty($email) || empty($password)) {
        $error = "Email and Password are required.";
    } else {
        $userObj = new User();
        $data = $userObj->findByEmail($email);

        echo "<strong>Database Query Result:</strong><br>";
        if ($data) {
            echo "✓ User found in database!<br>";
            echo "User ID: " . $data['id'] . "<br>";
            echo "User Name: " . htmlspecialchars($data['name']) . "<br>";
            echo "User Email: " . htmlspecialchars($data['email']) . "<br>";
            echo "Password Hash in DB: " . htmlspecialchars(substr($data['password_hash'], 0, 30)) . "...<br>";
            echo "Hash Length: " . strlen($data['password_hash']) . "<br><br>";
            
            $verify_result = password_verify($password, $data['password_hash']);
            echo "<strong>Password Verification:</strong><br>";
            echo "password_verify() returned: " . ($verify_result ? 'TRUE ✓' : 'FALSE ✗') . "<br>";
            
            if ($verify_result) {
                echo "<strong style='color: green;'>Login should succeed!</strong><br>";
            } else {
                echo "<strong style='color: red;'>Password does not match!</strong><br>";
            }
        } else {
            echo "✗ No user found with this email<br>";
        }
        echo "</div>";

       if ($data && password_verify($password, $data['password_hash'])) {
    // Clear any admin session first
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['is_admin']);
    
    // Set user session
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['user_name'] = $data['name'];
    $_SESSION['user_email'] = $data['email'];
    
    header('Location: index.php');
    exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Login - Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm" style="max-width: 380px; width: 100%;">
        <div class="card-body">
            <h3 class="card-title mb-3 text-center">User Login</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Login</button>
            </form>

            <p class="mt-3 text-center mb-0">
                Don't have an account?
                <a href="user_register.php">Register</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
