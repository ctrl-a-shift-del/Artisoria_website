<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Home</title>
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
            padding-top: 30px; /* Adjusted top padding to give space for the header */
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
        

        /* Navigation Buttons (Cart, Orders, Account) */
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

        /* Plus Icon Button */
        .plus-icon {
            width: 80px; /* Reduced size */
            height: 80px; /* Reduced size */
            border-radius: 50%;
            background-color: black;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            position: fixed;
            bottom: 30px; /* Positioned just above the bottom */
            left: 50%;
            transform: translateX(-50%); /* Center horizontally */
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2.5px solid white; /* White border around the icon */
            text-decoration: none; /* Removed underline */
        }

        .plus-icon:hover {
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
        <a href="buyer_cart.php">Cart</a>
        <a href="buyer_orders.php">Orders</a>
        <a href="account.php">Account</a>
    </div>

    <!-- Plus Icon Button -->
    <a href="buyer_explore.php" class="plus-icon">+</a>

</body>
</html>
