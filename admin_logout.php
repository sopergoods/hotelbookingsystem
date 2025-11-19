<?php
session_start();
// Clear ALL session variables
session_unset();
session_destroy();
header('Location: admin_login.php');
exit;
?>