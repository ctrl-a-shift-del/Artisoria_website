<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    echo "<script>alert('Unauthorized access!'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid product ID!'); window.location.href='my_products.php';</script>";
    exit();
}

$product_id = $_GET['id'];
$seller_id = $_SESSION['user_id'];

// Step 1: Delete related order items (fixes foreign key constraint error)
$deleteOrdersStmt = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
$deleteOrdersStmt->bind_param("i", $product_id);
$deleteOrdersStmt->execute();
$deleteOrdersStmt->close();

// Step 2: Delete product
$deleteStmt = $conn->prepare("DELETE FROM products WHERE product_id = ? AND seller_id = ?");
$deleteStmt->bind_param("ii", $product_id, $seller_id);

if ($deleteStmt->execute()) {
    echo "<script>alert('Product deleted successfully!'); window.location.href='my_products.php';</script>";
} else {
    echo "<script>alert('Error deleting product: " . addslashes($deleteStmt->error) . "'); window.location.href='my_products.php';</script>";
}

$deleteStmt->close();
$conn->close();
?>
