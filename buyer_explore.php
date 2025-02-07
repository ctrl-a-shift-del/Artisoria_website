<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM products ORDER BY product_id ASC");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (!isset($_SESSION['explore_index'])) {
    $_SESSION['explore_index'] = 0;
}
$currentIndex = $_SESSION['explore_index'];

$product = !empty($products) ? $products[$currentIndex] : null;

// Fetch average rating
$average_rating = null;
if ($product) {
    $stmt = $conn->prepare("SELECT AVG(rating) AS average_rating FROM reviews WHERE product_id = ?");
    $stmt->bind_param("i", $product['product_id']);
    $stmt->execute();
    $stmt->bind_result($average_rating);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Products</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: black;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }
        .product-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 0;
        }
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?php echo htmlspecialchars($product['image_1'] ?? ''); ?>');
            background-size: cover;
            background-position: center;
            filter: blur(10px);
            z-index: -1;
            transition: background-image 0.5s ease-in-out;
        }
        .image-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 80vh;
        }
        .product-image {
            width: auto;
            max-width: 90%;
            max-height: 80vh;
            object-fit: contain;
            transition: opacity 0.3s ease-in-out;
        }
        .product-details {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 15px;
            background: rgba(0, 0, 0, 0.8);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        .details-content {
            width: 70%;
        }
        .buttons {
            width: 30%;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn {
            background: #fff;
            color: black;
            padding: 12px;
            border-radius: 50%;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }
        .btn:hover {
            background: #333;
            color: white;
        }
        .home-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            background: white;
            color: black;
            font-size: 22px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            font-weight: bold;
            z-index: 1000;
        }
        .home-btn:hover {
            background: #ddd;
        }
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 4px solid transparent;
            border-top: 4px solid white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <a href="buyer_home.php" class="home-btn">←</a>
    <div class="loading-spinner" id="loadingSpinner"></div>
    <div class="product-container" id="productContainer" style="display:none;">
        <!-- Background Image (Blurred) -->
        <div class="background-image" id="backgroundImage"></div>
        
        <div class="image-wrapper">
            <img id="productImage" class="product-image" src="<?php echo htmlspecialchars($product['image_1'] ?? ''); ?>" alt="Product Image">
        </div>

        <div class="product-details" id="productDetails">
            <div class="details-content">
                <h3><?php echo htmlspecialchars($product['name'] ?? ''); ?></h3>
                <p class="price"><strong>Price:</strong> $<?php echo htmlspecialchars($product['price'] ?? ''); ?></p>
                <p><strong>Average Rating:</strong> <?php echo $average_rating ? number_format($average_rating, 1) : 'No ratings yet'; ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description'] ?? ''); ?></p>
            </div>
            <div class="buttons">
                <a href="#" class="btn" id="addToCartBtn">🛒</a>
                <a href="#" class="btn" id="viewReviewsBtn">⭐</a>
            </div>
        </div>
    </div>

    <script>
        let products = <?php echo json_encode($products); ?>;
        let currentIndex = <?php echo $currentIndex; ?>;
        let images = products[currentIndex] ? [products[currentIndex].image_1, products[currentIndex].image_2, products[currentIndex].image_3].filter(Boolean) : [];
        let currentImageIndex = 0;

        function updateImage() {
            if (images.length > 0) {
                document.getElementById("productImage").src = images[currentImageIndex];
                document.getElementById("backgroundImage").style.backgroundImage = "url('" + images[currentImageIndex] + "')";
            }
        }

        function nextImage() {
            if (images.length > 1) {
                currentImageIndex = (currentImageIndex + 1) % images.length;
                updateImage();
            }
        }

        function prevImage() {
            if (images.length > 1) {
                currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
                updateImage();
            }
        }

        function nextProduct() {
            if (currentIndex < products.length - 1) {
                currentIndex++;
                updateProduct();
            }
        }

        function prevProduct() {
            if (currentIndex > 0) {
                currentIndex--;
                updateProduct();
            }
        }

        function updateProduct() {
            let product = products[currentIndex];
            if (!product) return;

            images = [product.image_1, product.image_2, product.image_3].filter(Boolean);
            currentImageIndex = 0;
            updateImage();
            document.querySelector(".details-content h3").innerText = product.name;
            document.querySelector(".price").innerText = "Price: $" + product.price;
            document.querySelector(".details-content p:nth-child(3)").innerText = "Average Rating: " + (product.average_rating ? parseFloat(product.average_rating).toFixed(1) : "No ratings yet");
            document.querySelector(".details-content p:nth-child(4)").innerText = "Description: " + product.description;
            document.getElementById("addToCartBtn").dataset.productId = product.product_id;
            document.getElementById("viewReviewsBtn").dataset.productId = product.product_id;

            document.getElementById("productDetails").style.transform = "translateY(0)";
        }

        // Touch/swipe gestures for mobile
        let touchStart = 0;
        document.body.addEventListener("touchstart", (e) => {
            touchStart = e.changedTouches[0].pageX;
        });

        document.body.addEventListener("touchend", (e) => {
            let touchEnd = e.changedTouches[0].pageX;
            if (touchEnd - touchStart > 50) prevProduct();
            if (touchStart - touchEnd > 50) nextProduct();
        });

        // Keyboard navigation (arrow keys)
        document.body.addEventListener("keydown", (e) => {
            if (e.key === "ArrowRight") nextImage();
            if (e.key === "ArrowLeft") prevImage();
            if (e.key === "ArrowDown") nextProduct();
            if (e.key === "ArrowUp") prevProduct();
        });

        // Mouse wheel navigation
        document.body.addEventListener("wheel", (e) => {
            if (e.deltaY > 0) nextProduct();
            if (e.deltaY < 0) prevProduct();
        });

        // Add to Cart Button
        document.getElementById("addToCartBtn").addEventListener("click", function(e) {
            e.preventDefault();
            let productId = this.dataset.productId;
            fetch(`add_to_cart.php?product_id=${productId}`)
                .then(response => response.text())
                .then(data => {
                    alert("Product added to cart!");
                })
                .catch(error => console.error('Error:', error));
        });

        // View Reviews Button
        document.getElementById("viewReviewsBtn").addEventListener("click", function(e) {
            e.preventDefault();
            let productId = this.dataset.productId;
            window.open(`product_reviews.php?product_id=${productId}`, '_blank');
        });

        // Load content on page load
        window.onload = function () {
            document.getElementById("loadingSpinner").style.display = "none";
            document.getElementById("productContainer").style.display = "block";
            updateImage();
        };
    </script>
</body>
</html>
