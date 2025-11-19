<?php
include_once 'user_auth.php';

session_unset();
session_destroy();

header('Location: index.php');
exit;
?>
