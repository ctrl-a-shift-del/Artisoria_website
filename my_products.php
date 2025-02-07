<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: index.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ?");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products</title>
    <style>
        /* General Styling */
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #000;
            color: white;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            font-size: 2rem;
        }

        /* Back to Home Button */
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 10px 15px;
            background-color: #000;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 1rem;
            border: 2px solid white;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background-color: white;
            color: black;
        }

        /* Product Container */
        .product-container {
            background-color: #111;
            padding: 20px;
            margin: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
            border: 1px solid #fff;
        }

        .product-container h3 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .product-container p {
            font-size: 1rem;
            margin: 5px 0;
        }

        .product-images img {
            width: 100%;
            max-width: 200px;
            margin: 10px 5px;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(255, 255, 255, 0.2);
        }

        /* Buttons Styling */
        .btn, .edit-btn, .delete-btn, .add-product {
            display: inline-block;
            padding: 10px 15px;
            background-color: #000;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            border: 2px solid white;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn:hover, .edit-btn:hover, .delete-btn:hover, .add-product:hover {
            background-color: white;
            color: black;
        }

        /* Add Product Button */
        .add-product {
            display: inline-block;
            margin: 10px;
            margin-left: 10px; /* Ensures spacing from the left edge */
            padding: 10px 15px;
            background-color: #000;
            color: white;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
            border: 2px solid white;
            transition: all 0.3s ease;
        }

        /* Product Actions */
        .product-actions {
            display: flex;
            justify-content: flex-start;
            gap: 12px;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .product-container {
                margin: 10px;
                padding: 15px;
            }

            .product-container h3 {
                font-size: 1.5rem;
            }

            .product-images img {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>

    <!-- Back to Home Button -->
    <a href="seller_home.php" class="back-button">Back to Home</a>

    <h2>My Products</h2>

    <!-- Add Product Button -->
    <a href="add_product.php" class="add-product">Add Product</a>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product-container">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>

            <div class="product-images">
                <?php
                for ($i = 1; $i <= 10; $i++) {
                    $imageColumn = "image_$i";
                    if (!empty($row[$imageColumn])) {
                        echo '<img src="' . htmlspecialchars($row[$imageColumn]) . '" alt="Product Image">';
                    }
                }
                ?>
            </div>

            <h4>Average Rating:</h4>
            <?php
            // Fetch average rating for this product
            $stmt_rating = $conn->prepare("SELECT AVG(rating) AS average_rating FROM reviews WHERE product_id = ?");
            $stmt_rating->bind_param("i", $row['product_id']);
            $stmt_rating->execute();
            $stmt_rating->bind_result($average_rating);
            $stmt_rating->fetch();
            $stmt_rating->close();
            ?>
            <p>Rating: <?php echo ($average_rating ? number_format($average_rating, 1) : "No ratings yet"); ?> / 5</p>

            <!-- Product Actions -->
            <div class="product-actions">
                <a href="product_reviews.php?product_id=<?php echo $row['product_id']; ?>" class="btn" target="_blank">View Reviews</a>
                <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" class="edit-btn">Edit</a>
                <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
            </div>
        </div>
    <?php endwhile; ?>

</body>
</html>
