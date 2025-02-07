<?php
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"]; // Buyer or Seller selection

    $query = "SELECT * FROM users WHERE email='$email' AND user_type='$user_type'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
            session_start();
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["user_type"] = $row["user_type"];

            if ($row["user_type"] == "buyer") {
                header("Location: buyer_home.php");
            } else {
                header("Location: seller_home.php");
            }
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisoria - Login</title>
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

    input, button {
        padding: 10px;
        margin: 10px 0;
        border-radius: 15px; /* Consistent rounded corners */
        background-color: #222;
        color: white;
        border: none;
    }

    input::placeholder {
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

    /* Remove borders and shadows for email/password fields */
    input[type="email"], input[type="password"] {
        border: none;
        background-color: #222;
        color: white;
    }

    /* Toggle Button Styles */
    .toggle-container {
        display: flex;
        justify-content: center;
        margin: 10px 0;
        width: 100%; /* Make sure it takes full width for centering */
    }

    .toggle-container label {
        display: inline-block;
        width: 100%; /* Ensure the label takes up the full width of the container */
    }

    .toggle-container input[type="radio"] {
        display: none; /* Hide radio buttons */
    }

    .toggle-label {
        display: inline-block;
        width: 100%; /* Make the width 100% of the parent container */
        padding: 10px 0; /* Adjusted padding for better alignment */
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
        <input type="email" name="email" required placeholder="Enter Email">
        <input type="password" name="password" autocomplete="new-password" required placeholder="Enter Password">

        <!-- Toggle for Buyer/Seller -->
        <div class="toggle-container">
            <label>
                <input type="radio" name="user_type" value="buyer" checked>
                <div class="toggle-label">Buyer</div>
            </label>
            <label>
                <input type="radio" name="user_type" value="seller">
                <div class="toggle-label">Seller</div>
            </label>
        </div>

        <button type="submit">Login</button>
    </form>

    <a href="signup.php">New user? Sign up here!</a>

</body>
</html>
