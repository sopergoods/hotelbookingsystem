<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
        :root {
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --accent-color: #3b82f6;
            --text-muted: #94a3b8;
        }
        
        body { 
            background-color: #f8fafc; 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .sidebar { 
            width: 260px; 
            min-height: 100vh; 
            background: var(--sidebar-bg);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link { 
            color: var(--text-muted);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
            transform: translateX(4px);
        }
        
        .sidebar .nav-link.active { 
            color: #fff; 
            background-color: var(--accent-color);
        }
        
        .brand-text { 
            letter-spacing: 0.05em; 
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .admin-info {
            background: rgba(255,255,255,0.05);
            border-radius: 0.5rem;
            padding: 1rem;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            font-weight: 500;
        }
        
        .btn-logout:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }
        
        main {
            padding: 2rem;
        }
        
        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar text-white d-flex flex-column p-3">
        <a href="dashboard.php" class="d-flex align-items-center mb-4 text-white text-decoration-none">
            <span class="brand-text">HOTEL ADMIN</span>
        </a>
        
        <hr class="border-secondary opacity-25">
        
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':''; ?>">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="rooms_index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='rooms_index.php'?'active':''; ?>">
                    Rooms
                </a>
            </li>
            <li>
                <a href="bookings_index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='bookings_index.php'?'active':''; ?>">
                    Bookings
                </a>
            </li>
        </ul>
        
        <hr class="border-secondary opacity-25">
        
        <div class="admin-info mb-3">
            <small class="d-block text-secondary mb-1">Logged in as</small>
            <strong class="text-white"><?= htmlspecialchars(admin_current_username()) ?></strong>
        </div>
        
        <a href="admin_logout.php" class="btn btn-danger btn-logout">
            Logout
        </a>
        
        <hr class="border-secondary opacity-25">
        
        <div class="small text-secondary">&copy; <?php echo date('Y'); ?> Hotel System</div>
    </nav>

    <main class="flex-grow-1">
        <div class="page-header">
            <h1 class="h3 mb-0">
                <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?>
            </h1>
        </div>