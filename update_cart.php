<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

$cart_id = $_POST['cart_id'];
$action = $_POST['action'];

$stmt = $conn->prepare("SELECT quantity FROM cart WHERE cart_id = ?");
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_quantity = $row['quantity'];

if ($action == 'decrease') {
    if ($current_quantity == 1) {
        // Remove item from cart if quantity is 1
        $delete_stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
        $delete_stmt->bind_param("i", $cart_id);
        $delete_stmt->execute();
    } else {
        // Decrease quantity by 1
        $new_quantity = $current_quantity - 1;
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        $update_stmt->bind_param("ii", $new_quantity, $cart_id);
        $update_stmt->execute();
    }
} elseif ($action == 'increase') {
    $new_quantity = $current_quantity + 1;
    $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $update_stmt->bind_param("ii", $new_quantity, $cart_id);
    $update_stmt->execute();
}

header("Location: buyer_cart.php"); // Redirect back to cart page
exit();
?>
