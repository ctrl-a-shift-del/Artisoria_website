<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;

// Fetch all reviews for the product
$stmt = $conn->prepare("SELECT r.rating, r.review_text, u.name AS buyer_name FROM reviews r INNER JOIN users u ON r.buyer_id = u.user_id WHERE r.product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews</title>
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

        /* Review Section */
        .reviews {
            width: 90%;
            max-width: 800px;
            background-color: #111;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
            border: 1px solid #fff;
            box-sizing: border-box;
        }

        .review-item {
            margin-bottom: 20px;
        }

        .review-item p {
            margin: 5px 0;
        }

        .review-item strong {
            font-weight: bold;
        }

        /* No Reviews Yet Text */
        .no-reviews {
            text-align: center;
            font-size: 1.5rem;
            color: #aaa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            h2 {
                font-size: 2rem;
            }

            .reviews {
                padding: 15px;
                width: 95%;
            }
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <!--removed it -->

    <h2>Product Reviews</h2>

    <!-- Review Section -->
    <div class="reviews">
        <!-- Display reviews -->
        <?php if (!empty($reviews)): ?>
            <ul>
                <?php foreach ($reviews as $review): ?>
                    <li class="review-item">
                        <p><strong><?php echo htmlspecialchars($review['buyer_name']); ?></strong> (Rating: <?php echo htmlspecialchars($review['rating']); ?>/5)</p>
                        <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        <hr>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-reviews">No reviews yet for this product.</p>
        <?php endif; ?>
    </div>

</body>
</html>
