<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include "database.php";

$user_id = $_SESSION["user_id"];
$query = "SELECT name, email FROM users WHERE user_id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <style>
        body {
            font-family: "SF Pro Display", "SF Pro Icons", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            background-color: #000;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            margin: 0;
            padding-top: 20px;
            
        }

        h1 {
            font-size: 3rem; /* Slightly smaller title */
            color: white;
            margin-bottom: 30px; /* Space below the heading */
            font-weight: 700;
        }

        .account-details {
            background-color: #111;
            padding: 10px;
            border-radius: 15px;
            width: 80%;
            max-width: 500px;
            text-align: center; /* Center the content */
            margin-bottom: 50px;
            margin-top: 30px;
        }

        .account-details p {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .account-details strong {
            font-weight: 600;
        }

        /* Button styling */
        button {
            padding: 12px 20px;
            margin: 10px;
            font-size: 1rem;
            border-radius: 15px;
            background-color: black;
            color: white;
            border: 1px solid white;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 300px; /* Limit button width */
        }

        button:hover {
            background-color: white;
            color: black;
            border: 1px solid black;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 1); /* Glow effect */
        }

        /* Red delete button styling (default red text, border, black background) */
        button.delete {
            background-color: black;
            border: 1.5px solid red;
            color: red;
            max-width: 300px; /* Limit button width */
            width: 100%;
            
        }

        button.delete:hover {
            background-color: red;
            color: black;
            
            box-shadow: 0px 0px 10px rgba(255, 0, 0, 1);
            

        }

        /* Ensure all buttons are equally sized */
        a, form button {
            text-decoration: none;
            width: 100%;
            max-width: 300px; /* Equal width for all buttons */
        }
    </style>
</head>
<body>

    <h1>Account Details</h1>

    <div class="account-details">
        <p><strong>Name:</strong> <?php echo $user["name"]; ?></p>
        <p><strong>Email:</strong> <?php echo $user["email"]; ?></p>
    </div>

    <a href="<?php echo $_SESSION["user_type"] == 'buyer' ? 'buyer_home.php' : 'seller_home.php'; ?>"><button>Back to Home</button></a>
    <a href="logout.php"><button>Logout</button></a>

    <!-- Delete Account Form -->
    <form method="POST" action="delete_account.php" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
        <button type="submit" name="delete_account" class="delete">Delete Account</button>
    </form>

</body>
</html>
