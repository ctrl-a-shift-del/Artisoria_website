<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Address</title>
    <style>
        body {
            font-family: "SF Pro Display", "SF Pro Icons", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            background-color: #000;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 80px;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 350px;
            background-color: #111;
            padding: 20px;
            border-radius: 15px;
        }

        textarea, input {
            padding: 12px;
            margin: 10px 0;
            border-radius: 15px;
            background-color: #222;
            color: white;
            border: none;
            resize: none; /* Prevent resizing for the textarea */
        }

        textarea::placeholder, input::placeholder {
            color: #bbb;
        }

        button {
            background-color: #000;
            color: white;
            cursor: pointer;
            border: 1px solid white;
            width: 250px;
            padding: 12px;
            margin: 20px auto;
            border-radius: 15px;
        }

        button:hover {
            background-color: white;
            color: black;
            border: 1px solid black;
        }

        a {
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
        }

        a:hover, a:active {
            color: #fff;
            text-shadow: 0px 0px 10px rgba(255, 255, 255, 1);
        }
    </style>
</head>
<body>

    <h2>Enter Address Details</h2>
    
    <form action="payment.php" method="post">
        <label>Address:</label><br>
        <textarea name="address" required placeholder="Enter your address"></textarea><br><br>

        <label>Phone Number:</label><br>
        <input type="text" name="phone_number" required pattern="[0-9]{10,15}" placeholder="Enter phone number"><br><br>

        <button type="submit">Proceed to Payment</button>
    </form>

</body>
</html>
