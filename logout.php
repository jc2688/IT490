<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to index.html
header("Location: index.html");
exit();
?>