<?php
// Start the session
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the home page or login page
header("Location: ../index.html");  // Or change to login.php if needed
exit();
?>
