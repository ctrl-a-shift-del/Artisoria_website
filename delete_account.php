<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include "database.php";

$user_id = $_SESSION["user_id"];

// Delete user from database
$query = "DELETE FROM users WHERE user_id='$user_id'";
mysqli_query($conn, $query);

// Destroy session
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
?>
