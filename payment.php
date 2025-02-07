<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['phone_number'] = $_POST['phone_number'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        /* Main Body Styles */
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

        /* Heading Styles */
        h2 {
            text-align: center;
            font-size: 2.5rem; /* You can adjust font size here */
            margin-top: 90px;
            margin-top: 100px
            margin-bottom: 30px; /* Adjust vertical spacing as needed */
            font-weight: 600;
        }

        /* Form Container Styles */
        form {
            display: flex;
            flex-direction: column;
            width: 400px; /* Controls form width */
            background-color: #111;
            padding: 20px;
            border-radius: 15px; /* Rounded corners for form */
        }

        /* Input and Textarea Fields Styling */
        input {
            padding: 12px;
            margin: 0px 0; /* You can increase or decrease margin to adjust vertical spacing between fields */
            border-radius: 15px;
            background-color: #222;
            color: white;
            border: none;
        }

        /* Placeholder Text Styling */
        input::placeholder {
            color: #bbb;
        }

        /* Submit Button Styles */
        button {
            background-color: #000;
            color: white;
            cursor: pointer;
            border: 1px solid white;
            width: 250px; /* Button width, adjust to fit your layout */
            padding: 12px;
            margin: 20px auto; /* Adjust horizontal margin for centering */
            border-radius: 15px; /* Rounded button corners */
        }

        /* Button Hover Effect */
        button:hover {
            background-color: white;
            color: black;
            border: 1px solid black;
        }

        /* Label Styles */
        label {
            font-size: 1.2rem; /* Adjust label font size here */
            margin-bottom: 0px; /* Space between label and input field */
        }
    </style>
</head>
<body>

    <!-- Page Heading -->
    <h2>Enter Payment Details</h2>
    
    <!-- Form for Payment Details -->
    <form action="otp.php" method="post">
        <!-- Card Number Field -->
        <label>Card Number:</label><br>
        <input type="text" name="card_number" required pattern="[0-9]{16}" placeholder="Enter 16-digit Card Number"><br><br>

        <!-- CVV Field -->
        <label>CVV:</label><br>
        <input type="text" name="cvv" required pattern="[0-9]{3}" placeholder="Enter 3-digit CVV"><br><br>

        <!-- Expiry Date Field -->
        <label>Expiry Date:</label><br>
        <input type="month" name="expiry" required><br><br>

        <!-- Name on Card Field -->
        <label>Name on Card:</label><br>
        <input type="text" name="name_on_card" required placeholder="Enter Name on Card"><br><br>

        <!-- Submit Button -->
        <button type="submit">Proceed to OTP</button>
    </form>

</body>
</html>
