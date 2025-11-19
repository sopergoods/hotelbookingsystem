<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function user_is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function user_require_login() {
    if (!user_is_logged_in()) {
        header('Location: user_login.php');
        exit;
    }
}

function user_current_name() {
    return $_SESSION['user_name'] ?? null;
}

function admin_is_logged_in() {
    return !empty($_SESSION['admin_id']) && !empty($_SESSION['is_admin']);
}

function admin_require_login() {
    if (!admin_is_logged_in()) {
        header('Location: admin_login.php');
        exit;
    }
}

function admin_current_username() {
    return $_SESSION['admin_username'] ?? null;
}
?>
