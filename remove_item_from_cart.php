<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];

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

header("Location: buyer_cart.php");
exit();
