<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: index.php");
    exit();
}

include "database.php";

$seller_id = $_SESSION['user_id'];

// Fetch total number of orders
$order_query = "SELECT COUNT(*) AS total_orders FROM order_items 
                WHERE product_id IN (
                    SELECT product_id FROM products 
                    WHERE seller_id = $seller_id
                )";
$order_result = mysqli_query($conn, $order_query);
$order_data = mysqli_fetch_assoc($order_result);
$total_orders = $order_data['total_orders'];

// Fixed revenue query
$revenue_query = "SELECT SUM(oi.price * oi.quantity) AS total_revenue 
                  FROM order_items oi
                  INNER JOIN products p ON oi.product_id = p.product_id
                  WHERE p.seller_id = $seller_id";
                  
$revenue_result = mysqli_query($conn, $revenue_query);

// Add error checking
if (!$revenue_result) {
    die("Query failed: " . mysqli_error($conn));
}

$revenue_data = mysqli_fetch_assoc($revenue_result);
$total_revenue = $revenue_data['total_revenue'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Home</title>
    <style>
        body {
            font-family: "SF Pro Display", "SF Pro Icons", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            background-color: #000;
            color: white;
            display: flex;
            justify-content: flex-start; /* Align items to the start of the page */
            align-items: flex-start;
            height: 100vh;
            margin: 10;
            flex-direction: column;
            text-align: center;
            padding-top: 30px;
        }

        /* Website Name (Header) */
        h1 {
            font-size: 6rem; /* Big size for the title */
            font-weight: 700; /* Bold for website name */
            color: white;
            width: 100%;
            text-align: center; /* Center horizontally */
            margin-top:0px;
            margin-bottom: 90px; /* Space between header and buttons */
        }

        /* Navigation Buttons (My Products, My Orders, Account) */
        .button-container {
            display: flex;
            flex-direction: column; /* Stack the buttons vertically */
            justify-content: center; /* Center buttons horizontally */
            align-items: center; /* Align items in the center */
            gap: 20px; /* Space between buttons */
            width: 320px; /* Fixed width for buttons */
            margin: 0 auto; /* Center the button container horizontally */
        }

        .button-container a {
            display: block;
            font-size: 1.2rem; /* Smaller text size */
            color: white;
            text-decoration: none;
            font-weight: 500;
            border-radius: 15px;
            padding: 12px;
            background-color: black;
            border: 1px solid white;
            transition: all 0.3s ease;
            width: 100%; /* Full width of the button container */
            text-align: center;
        }

        .button-container a:hover, .button-container a:active {
            background-color: white;
            color: black;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 1); /* Glow effect */
        }

    </style>
</head>
<body>

    <h1>Artisoria</h1>

    <!-- Navigation Buttons Container -->
    <div class="button-container">
        <a href="my_products.php">My Products</a>
        <a href="my_orders.php">My Orders</a>
        <a href="account.php">Account</a>
    </div>

</body>
</html>
