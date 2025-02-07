<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];
$address = $_SESSION['address'];
$phone_number = $_SESSION['phone_number'];
$total_price = 0;

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE buyer_id = ?");
    $stmt->bind_param("i", $buyer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Your cart is empty!");
    }

    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];

        $product_stmt = $conn->prepare("SELECT price FROM products WHERE product_id = ?");
        $product_stmt->bind_param("i", $product_id);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();

        if ($product_result->num_rows === 0) {
            throw new Exception("Product not found!");
        }

        $product_row = $product_result->fetch_assoc();
        $product_price = $product_row['price'];
        $total_price += $product_price * $quantity;

        $cart_items[] = ['product_id' => $product_id, 'quantity' => $quantity, 'price' => $product_price];
        $product_stmt->close();
    }

    $order_stmt = $conn->prepare("INSERT INTO orders (buyer_id, total_price, status, address, phone_number) VALUES (?, ?, 'pending', ?, ?)");
    $order_stmt->bind_param("idss", $buyer_id, $total_price, $address, $phone_number);

    if (!$order_stmt->execute()) {
        throw new Exception("Failed to create order!");
    }

    $order_id = $conn->insert_id;

    foreach ($cart_items as $item) {
        $order_item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $order_item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);

        if (!$order_item_stmt->execute()) {
            throw new Exception("Failed to add order items!");
        }

        $order_item_stmt->close();
    }

    $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE buyer_id = ?");
    $clear_cart_stmt->bind_param("i", $buyer_id);

    if (!$clear_cart_stmt->execute()) {
        throw new Exception("Failed to clear cart!");
    }

    $conn->commit();
    $_SESSION['message'] = "Order placed successfully!";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['message'] = $e->getMessage();
}

$conn->close();
header("Location: buyer_orders.php");
exit();
