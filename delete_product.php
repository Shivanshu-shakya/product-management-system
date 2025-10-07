<?php
require 'includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    die("Access denied! Admin privileges required.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->execute([$id]);
header("Location: products.php");
exit;
?>