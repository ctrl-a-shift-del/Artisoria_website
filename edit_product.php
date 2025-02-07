<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid product ID.";
    exit();
}

$product_id = $_GET['id'];
$seller_id = $_SESSION['user_id'];

// Fetch the existing product details
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $updateStmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE product_id = ?");
    $updateStmt->bind_param("sdsi", $name, $price, $description, $product_id);

    if ($updateStmt->execute()) {
        echo "Product updated successfully!";
        header("Refresh: 2; URL=my_products.php");
    } else {
        echo "Error updating product: " . $updateStmt->error;
    }

    $updateStmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        /* General Styling */
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #000;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Back Button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 8px 12px;
            background-color: #000;
            color: white;
            border: 2px solid white;
            border-radius: 15px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background-color: white;
            color: black;
        }

        /* Heading */
        h2 {
            font-size: 2.5rem;
            margin: 40px 0;
            text-align: center;
        }

        /* Form Container */
        .form-container {
            width: 90%;
            max-width: 600px;
            background-color: #111;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
            border: 1px solid #fff;
            box-sizing: border-box;
        }

        label {
            font-size: 1.2rem;
            font-weight: bold;
            display: block;
            margin-top: 12px;
        }

        input, textarea {
            width: 95%;
            padding: 12px;
            border-radius: 12px;
            border: 2px solid white;
            background: black;
            color: white;
            font-size: 1rem;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: black;
            color: white;
            border-radius: 15px;
            font-size: 1rem;
            font-weight: bold;
            border: 2px solid white;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: white;
            color: black;
        }

        /* Responsive */
        @media (max-width: 768px) {
            h2 {
                font-size: 2rem;
            }

            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <a href="my_products.php" class="back-button">Back</a>

    <h2>Edit Product</h2>

    <!-- Edit Product Form -->
    <div class="form-container">
        <form method="post">
            <label>Product Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br><br>

            <label>Price:</label>
            <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>

            <label>Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>

            <button type="submit">Update Product</button>
        </form>
    </div>

</body>
</html>
