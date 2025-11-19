<?php include_once 'user_auth.php'; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">MyHotel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <?php if (user_is_logged_in()): ?>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="my_bookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">Hi, <?= htmlspecialchars(user_current_name()) ?></span>
                    </li>
                    <li class="nav-item">
                        <a href="user_logout.php" class="btn btn-danger btn-sm ms-2">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-2">
                        <a href="user_login.php" class="btn btn-outline-primary btn-sm">Login</a>
                    </li>
                    <li class="nav-item me-2">
                        <a href="user_register.php" class="btn btn-primary btn-sm">Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_login.php" class="btn btn-outline-secondary btn-sm" title="Admin Login">
                            🔐 Admin
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>