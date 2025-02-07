<?php
session_start();
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $user_type = $_POST["user_type"]; // Buyer or Seller

    $query = "INSERT INTO users (name, email, password, user_type) VALUES ('$name', '$email', '$password', '$user_type')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Signup Successful! Redirecting to login...'); window.location.href = 'index.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisoria - Sign Up</title>
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

        h1 {
            text-align: center;
            font-size: 5rem; /* Big size for the title */
            margin-bottom: 40px;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 300px; /* Same width as email input */
            background-color: #111;
            padding: 20px;
            border-radius: 15px; /* Rounded corners for the form */
        }

        input, button, select {
            padding: 10px;
            margin: 10px 0;
            border-radius: 15px; /* Consistent rounded corners */
            background-color: #222;
            color: white;
            border: none;
        }

        input::placeholder, select {
            color: #bbb;
        }

        button {
            background-color: #000;
            color: white;
            cursor: pointer;
            border: 1px solid white;
            width: 250px; /* Adjusted width */
            margin: 10px auto; /* Reduced margin for better spacing */
        }

        button:hover {
            background-color: white;
            color: black;
            border: 1px solid black;
        }

        /* Remove borders and shadows for name, email, and password fields */
        input[type="text"], input[type="email"], input[type="password"], select {
            border: none;
            background-color: #222;
            color: white;
        }

        /* Horizontal Toggle Button Styles */
        .toggle-container {
            display: flex;
            justify-content: space-between; /* Remove space between buttons */
            margin: 10px 0;
            width: 100%; /* Ensure it takes full width */
        }

        .toggle-container label {
            display: flex;
            width: 50%; /* Make both buttons take equal space */
            align-items: center; /* Center the content vertically */
            justify-content: center; /* Center the content horizontally */
            padding: 0; /* Remove padding between buttons */
        }

        .toggle-container input[type="radio"] {
            display: none; /* Hide radio buttons */
        }

        .toggle-label {
            display: inline-block;
            width: 100%; /* Make the label take up the full width */
            padding: 10px 0;
            font-size: 14px; /* Font size is slightly bigger */
            text-align: center;
            font-weight: 600;
            color: white;
            background-color: #000;
            transition: color 0.3s ease;
            cursor: pointer;
            border-radius: 15px; /* Rounded corners */
        }

        /* When checked */
        .toggle-container input[type="radio"]:checked + .toggle-label {
            background-color: #fff;
            color: black;
        }

        /* When unchecked */
        .toggle-container input[type="radio"]:not(:checked) + .toggle-label {
            background-color: #000;
            color: white;
        }

        .toggle-label span {
            display: inline-block; /* Make sure text doesn't break into multiple lines */
            white-space: nowrap; /* Prevent text from wrapping */
        }

        /* Hover effect on Sign Up with bright glow */
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

    <h1>Artisoria</h1>

    <form method="POST">
        <input type="text" name="name" required placeholder="Enter Name">
        <input type="email" name="email" required placeholder="Enter Email">
        <input type="password" name="password" required placeholder="Enter Password">

        <!-- Horizontal Toggle for Buyer/Seller -->
        <div class="toggle-container">
            <label class="toggle">
                <input type="radio" name="user_type" value="buyer" checked>
                <div class="toggle-label">
                    <span>Buyer</span>
                </div>
            </label>
            <label class="toggle">
                <input type="radio" name="user_type" value="seller">
                <div class="toggle-label">
                    <span>Seller</span>
                </div>
            </label>
        </div>

        <button type="submit">Sign Up</button>
    </form>

    <a href="index.php">Already have an account? Login</a>

</body>
</html>
