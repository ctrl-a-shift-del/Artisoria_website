<?php
session_start();
require 'database.php';

// Check if user is logged in as buyer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

$order_id = $_POST['order_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$review_text = $_POST['review_text'] ?? null;

if ($order_id && $product_id && $rating && $review_text) {
    // Check if a review already exists for this product by the buyer
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? AND buyer_id = ?");
    $stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
    $stmt->execute();
    $existing_review = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($existing_review) {
        // Update existing review
        $stmt = $conn->prepare("UPDATE reviews SET rating = ?, review_text = ? WHERE product_id = ? AND buyer_id = ?");
        $stmt->bind_param("isii", $rating, $review_text, $product_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new review
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, buyer_id, rating, review_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $product_id, $_SESSION['user_id'], $rating, $review_text);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: buyer_orders.php?order_id=" . $order_id);
exit();
