<?php
session_start();

// Destroy the session and log the user out
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
