<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['product_id'])) {  // Use GET instead of POST
    $buyer_id = $_SESSION['user_id'];
    $product_id = $_GET['product_id'];  // Use GET to capture the product_id

    // Check if item is already in the cart
    $check_stmt = $conn->prepare("SELECT quantity FROM cart WHERE buyer_id = ? AND product_id = ?");
    $check_stmt->bind_param("ii", $buyer_id, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // If exists, increase quantity
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE buyer_id = ? AND product_id = ?");
        $update_stmt->bind_param("ii", $buyer_id, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Else, insert new item
        $insert_stmt = $conn->prepare("INSERT INTO cart (buyer_id, product_id, quantity) VALUES (?, ?, 1)");
        $insert_stmt->bind_param("ii", $buyer_id, $product_id);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
// No need to redirect, as AJAX handles the response
?>