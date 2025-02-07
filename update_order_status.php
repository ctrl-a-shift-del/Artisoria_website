<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Update order status to "completed"
    $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Order #$order_id marked as completed.";
    } else {
        $_SESSION['error'] = "Failed to update order status.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to my_orders.php
    header("Location: my_orders.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: my_orders.php");
    exit();
}
