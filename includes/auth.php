<?php
// This file is now simplified since config.php handles the connection
// You can remove this file or keep it for future authentication functions

function requireAuth() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin() {
    requireAuth();
    if (!$_SESSION['user']['is_admin']) {
        die("Access denied! Admin privileges required.");
    }
}
?>