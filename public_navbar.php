<?php include_once 'user_auth.php'; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php" style="font-size: 1.5rem; letter-spacing: -0.5px;">
            MyHotel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item me-3">
                    <a class="nav-link fw-medium" href="index.php">Home</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link fw-medium" href="search.php">Search Rooms</a>
                </li>
                <?php if (user_is_logged_in()): ?>
                    <li class="nav-item me-3">
                        <a class="nav-link fw-medium" href="my_bookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle fw-medium" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?= htmlspecialchars(user_current_name()) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="my_bookings.php">My Bookings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="user_logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-2">
                        <a href="user_login.php" class="btn btn-outline-primary">Login</a>
                    </li>
                    <li class="nav-item me-2">
                        <a href="user_register.php" class="btn btn-primary">Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_login.php" class="btn btn-outline-secondary" title="Admin Login">
                            Admin
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>