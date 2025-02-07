<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['cart_id']) && isset($_GET['action'])) {
    $cart_id = $_GET['cart_id'];
    $action = $_GET['action'];

    if ($action === "increase") {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE cart_id = ?");
    } elseif ($action === "decrease") {
        $stmt = $conn->prepare("UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE cart_id = ?");
    }

    if ($stmt) {
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: buyer_cart.php");
exit();
