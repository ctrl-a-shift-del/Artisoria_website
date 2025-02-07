<?php
session_start();
require 'database.php';

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM products ORDER BY product_id ASC");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (!isset($_SESSION['explore_index'])) {
    $_SESSION['explore_index'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['direction'])) {
    if ($_POST['direction'] === "next" && $_SESSION['explore_index'] < count($products) - 1) {
        $_SESSION['explore_index']++;
    } elseif ($_POST['direction'] === "prev" && $_SESSION['explore_index'] > 0) {
        $_SESSION['explore_index']--;
    }
}

header("Location: buyer_explore.php");
exit();
