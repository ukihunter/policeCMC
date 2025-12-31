<?php
session_start();
require_once "../config/db.php";
require_once "../config/activity_logger.php";

if (isset($_SESSION["user_id"])) {
    logActivity($conn, 'logout', "User logged out");
}

session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logging out...</title>
    <script>
        // Clear localStorage to reset to dashboard for next login
        localStorage.removeItem('activeTab');
        // Redirect to login page
        window.location.href = '../login/login.php';
    </script>
</head>
<body>
    <p>Logging out...</p>
</body>
</html>