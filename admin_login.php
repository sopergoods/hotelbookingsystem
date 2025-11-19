<?php
session_start();
include_once 'Admin.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username and Password are required.";
    } else {
        $adminObj = new Admin();
        $admin = $adminObj->findByUsername($username);
        
        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Clear any user session first
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_email']);
            
            // Set admin session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['is_admin'] = true;
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login - Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .admin-card {
            border-radius: 15px;
            border: none;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg admin-card" style="max-width: 400px; width: 100%;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold">🔐 Admin Panel</h2>
                <p class="text-muted">Login to manage the hotel</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control form-control-lg"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                           placeholder="Enter admin username" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" 
                           placeholder="Enter password" required>
                </div>
                <button class="btn btn-primary btn-lg w-100" type="submit">
                    Login to Dashboard
                </button>
            </form>

            <hr class="my-4">

            <p class="text-center mb-0 text-muted small">
                Not an admin? <a href="user_login.php" class="text-decoration-none">User Login</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>