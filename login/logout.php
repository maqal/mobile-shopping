<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Optionally, redirect to the login page or home page
header("Location: ../login/login.html");
exit;
?>
