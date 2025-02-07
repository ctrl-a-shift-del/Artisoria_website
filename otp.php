<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['card_number'] = $_POST['card_number'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP</title>
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
            font-size: 3rem; /* You can adjust font size here */
            margin-bottom: 30px; /* Adjust vertical spacing as needed */
            font-weight: 600;
        }

        /* Form Container Styles */
        form {
            display: flex;
            flex-direction: column;
            width: 300px; /* Controls form width */
            background-color: #111;
            padding: 20px;
            border-radius: 15px; /* Rounded corners for form */
        }

        /* Input Fields Styling */
        input {
            padding: 12px;
            margin: 10px 0; /* You can adjust margin to control vertical spacing between fields */
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
            width: 250px; /* Adjust button width as needed */
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
            margin-bottom: 5px; /* Space between label and input field */
        }
    </style>
</head>
<body>

    <!-- Page Heading -->
    <h2>Enter OTP</h2>
    
    <!-- Form for OTP -->
    <form action="place_order.php" method="post">
        <!-- OTP Input Field -->
        <label>OTP:</label><br>
        <input type="text" name="otp" required pattern="[0-9]{4,6}" placeholder="Enter OTP"><br><br>

        <!-- Submit Button -->
        <button type="submit">Confirm Payment</button>
    </form>

</body>
</html>
