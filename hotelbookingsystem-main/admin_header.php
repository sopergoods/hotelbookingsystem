<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protect admin pages
include_once 'user_auth.php';
admin_require_login();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Hotel Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; }
        .sidebar { width: 240px; min-height: 100vh; }
        .sidebar .nav-link { color: #adb5bd; }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover { color: #fff; background-color: rgba(255,255,255,0.1); }
        .brand-text { letter-spacing: .06em; }
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar bg-dark text-white d-flex flex-column p-3">
        <a href="dashboard.php" class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <span class="fs-4 fw-bold brand-text">HOTEL ADMIN</span>
        </a>
        <hr class="border-secondary">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':''; ?>">Dashboard</a>
            </li>
            <li>
                <a href="rooms_index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='rooms_index.php'?'active':''; ?>">Rooms</a>
            </li>
            <li>
                <a href="bookings_index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='bookings_index.php'?'active':''; ?>">Bookings</a>
            </li>
        </ul>
        <hr class="border-secondary">
        
        <!-- Admin Info and Logout -->
        <div class="mb-2">
            <small class="text-secondary">Logged in as:</small><br>
            <strong class="text-white"><?= htmlspecialchars(admin_current_username()) ?></strong>
        </div>
        <a href="admin_logout.php" class="btn btn-outline-danger btn-sm">
            🚪 Logout
        </a>
        
        <hr class="border-secondary">
        <div class="small text-secondary">&copy; <?php echo date('Y'); ?> Hotel System</div>
    </nav>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?>
            </h1>
        </div>