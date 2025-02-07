<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Ensure price is a valid number
    if (!is_numeric($price) || $price <= 0) {
        echo "<script>alert('Invalid price value!'); window.location.href='add_product.php';</script>";
        exit();
    }

    // Image upload settings
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Allowed image types
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

    // Image columns in the database
    $imageColumns = [
        "image_1", "image_2", "image_3", "image_4", "image_5",
        "image_6", "image_7", "image_8", "image_9", "image_10"
    ];
    $imageValues = array_fill(0, 10, NULL); // Default all to NULL

    // Upload images and assign them to respective columns
    foreach ($imageColumns as $index => $columnName) {
        if (!empty($_FILES[$columnName]['name'])) {
            $fileType = $_FILES[$columnName]['type'];

            // Validate image type
            if (in_array($fileType, $allowedTypes)) {
                $fileName = uniqid() . "_" . basename($_FILES[$columnName]['name']);
                $targetFilePath = $uploadDir . $fileName;

                // Move file and store path
                if (move_uploaded_file($_FILES[$columnName]['tmp_name'], $targetFilePath)) {
                    $imageValues[$index] = $targetFilePath;
                }
            }
        }
    }

    // Insert product into database
    $stmt = $conn->prepare("
        INSERT INTO products (seller_id, name, price, description, 
        image_1, image_2, image_3, image_4, image_5, 
        image_6, image_7, image_8, image_9, image_10) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isdsssssssssss", $seller_id, $name, $price, $description, ...$imageValues);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location.href='my_products.php';</script>";
    } else {
        echo "<script>alert('Error: " . addslashes($stmt->error) . "'); window.location.href='add_product.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
