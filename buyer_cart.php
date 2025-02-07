<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: index.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("
    SELECT cart.cart_id, cart.quantity, products.product_id, products.name, products.price, products.image_1 
    FROM cart 
    JOIN products ON cart.product_id = products.product_id 
    WHERE cart.buyer_id = ?
");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <style>
        body {
            margin: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: black;
            color: white;
            padding: 20px;
        }

        h2 {
            font-family: 'SF Pro Display', sans-serif;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: white;
            font-size: 1rem;
            display: inline-block;
            margin-bottom: 20px;
        }

        a:hover {
            color: #ddd;
        }

        .cart-item {
            display: flex;
            align-items: center;
            background-color: #222;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .cart-item:hover {
            background-color: #333;
        }

        .cart-item img {
            width: 100px;
            height: auto;
            object-fit: cover;
            margin-right: 20px;
        }

        .cart-item h3 {
            font-size: 1.5rem;
            margin: 0;
        }

        .cart-item p {
            font-size: 1rem;
            margin: 5px 0;
        }

        .cart-item .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .cart-item .quantity-controls button {
            background-color: black;
            color: white;
            padding: 8px 12px;
            border: 2px solid white;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cart-item .quantity-controls button:hover {
            background-color: white;
            color: black;
        }

        .total-price {
            text-align: center;
            font-size: 1.5rem;
            margin-top: 20px;
            font-weight: bold;
        }

        .checkout-btn, .back-btn {
            display: inline-block;
            background-color: black;
            color: white;
            padding: 15px 25px;
            font-size: 1.2rem;
            border: 2px solid white;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .checkout-btn:hover, .back-btn:hover {
            background-color: white;
            color: black;
        }

    </style>
</head>
<body>

    <h2>My Cart</h2>
    <a href="buyer_home.php" class="back-btn">Back to Home</a>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($item['image_1']); ?>" alt="Product Image">
                <div>
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p><strong>Price:</strong> $<?php echo number_format($item['price'], 2); ?></p>
                    
                    <div class="quantity-controls">
                        <form action="update_cart.php" method="post" style="display:inline;">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <input type="hidden" name="action" value="decrease">
                            <button type="submit">-</button>
                        </form>

                        <span><?php echo $item['quantity']; ?></span>

                        <form action="update_cart.php" method="post" style="display:inline;">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <input type="hidden" name="action" value="increase">
                            <button type="submit">+</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="total-price">
            <p>Total Price: $<?php echo number_format($total_price, 2); ?></p>
        </div>

        <form action="address.php" method="post">
            <button type="submit" class="checkout-btn">Place Order</button>
        </form>
    <?php endif; ?>

</body>
</html>
