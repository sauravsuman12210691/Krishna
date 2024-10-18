<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: Login.php");
exit; // Ensure no further code is executed after redirection
?>
