<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Handle adding a new review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Check if the user has already reviewed this product
    $check_stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? AND buyer_id = ?");
    $check_stmt->bind_param("ii", $product_id, $buyer_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        // Insert the review into the database
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, buyer_id, rating, review_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $product_id, $buyer_id, $rating, $review_text);
        $stmt->execute();
        $stmt->close();
    }

    $check_stmt->close();

    // Redirect to the same page to see the updated orders
    header("Location: buyer_orders.php");
    exit();
}

// Fetch all orders for the buyer
$stmt = $conn->prepare("SELECT * FROM orders WHERE buyer_id = ? ORDER BY order_id DESC");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        /* General Page Styling */
        body {
            font-family: "SF Pro Display", "SF Pro Icons", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            background-color: #000;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            margin: 0;
            padding: 20px;
        }

        /* Back Button Styling */
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 8px 12px;
            background: #0000;
            color: white;
            border: 1px  #FFFFFF;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .back-button:hover {
            background: #FFFFFF;
            color: black;
        }

        /* Heading Styling */
        h2 {
            font-size: 2.5rem; /* Adjust font size here */
            margin-bottom: 30px; /* Adjust margin to control space below the heading */
            font-weight: 600;
            text-align: center;
        }

        /* Orders List Styling */
        ul {
            list-style-type: none;
            padding: 0;
            width: 80%;
            max-width: 800px;
            
        }

        /* Individual Order Styling */
        li {
            background-color: #111;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
        }

        /* Order Item Styling */
        h4 {
            margin-top: 20px;
            font-size: 1.2rem;
        }

        /* Review Form Styling */
        form {
            margin-top: 15px;
            padding: 15px;
            background-color: #222;
            border-radius: 10px;
            width: 390px;
        }

        input[type="number"], textarea {
            width: 370px; /* Full width input fields */
            padding: 10px;
            margin: 5px 0;
            border-radius: 10px;
            background-color: #333;
            color: white;
            border: 1px solid #444;
        }

        button {
            background-color: #000;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            border:  none;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: white;
            color: black;
            border: 1px solid black;
        }

        /* Styling for the 'No Orders' message */
        p {
            font-size: 1.2rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Back to Home Button -->
    <a href="buyer_home.php" class="back-button">Back to Home</a>

    <!-- Page Heading -->
    <h2>My Orders</h2>

    <!-- Orders List -->
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong>Order ID:</strong> <?php echo $row['order_id']; ?> <br>
                    <strong>Total:</strong> $<?php echo $row['total_price']; ?> <br>
                    <strong>Status:</strong> <?php echo $row['status']; ?> <br>

                    <!-- Display Order Items -->
                    <h4>Order Items:</h4>
                    <?php
                    $order_id = $row['order_id'];
                    $items_stmt = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = ?");
                    $items_stmt->bind_param("i", $order_id);
                    $items_stmt->execute();
                    $items_result = $items_stmt->get_result();

                    if ($items_result->num_rows > 0):
                        while ($item = $items_result->fetch_assoc()): ?>
                            <p>Product: <?php echo $item['name']; ?> - Quantity: <?php echo $item['quantity']; ?> - Price: $<?php echo $item['price']; ?></p>

                            <?php
                            // Check if the user has already reviewed this product
                            $review_stmt = $conn->prepare("SELECT rating, review_text FROM reviews WHERE product_id = ? AND buyer_id = ?");
                            $review_stmt->bind_param("ii", $item['product_id'], $buyer_id);
                            $review_stmt->execute();
                            $review_result = $review_stmt->get_result();
                            $review = $review_result->fetch_assoc();
                            $review_stmt->close();

                            if ($review): ?>
                                <!-- Display the user's review and rating -->
                                <div style="background-color: #333; padding: 10px; border-radius: 5px; margin-top: 10px;">
                                    <p><strong>Your Rating:</strong> <?php echo $review['rating']; ?>/5</p>
                                    <p><strong>Your Review:</strong> <?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                </div>
                            <?php else: ?>
                                <!-- Add Review Form for Each Product -->
                                <form action="buyer_orders.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                    
                                    <!-- Rating Input -->
                                    <label for="rating">Rating:</label><br>
                                    <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

                                    <!-- Review Text Area -->
                                    <label for="review_text">Review:</label><br>
                                    <textarea id="review_text" name="review_text" rows="4" required></textarea><br><br>

                                    <!-- Submit Button -->
                                    <button type="submit">Submit Review</button>
                                </form>
                            <?php endif; ?>
                        <?php endwhile;
                    else:
                        echo "<p>No items found.</p>";
                    endif;
                    $items_stmt->close();
                    ?>
                </li>
                <hr>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No orders yet.</p>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>