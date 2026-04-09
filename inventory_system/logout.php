<?php
// Include database connection to access session
include 'db.php';

// Destroy all session data
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
?>
