<?php
session_start();
session_unset();
session_destroy();
echo "Session cleared! <br>";
echo "<a href='user_login.php'>Go to User Login</a> | ";
echo "<a href='admin_login.php'>Go to Admin Login</a>";
?>