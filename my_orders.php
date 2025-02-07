<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: index.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Fetch orders for seller's products
$stmt = $conn->prepare("
    SELECT o.order_id, o.buyer_id, o.status, o.total_price, o.address, o.phone_number, 
           oi.product_id, oi.quantity, oi.price, p.name AS product_name, u.name AS buyer_name
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN users u ON o.buyer_id = u.user_id
    WHERE p.seller_id = ?
    ORDER BY o.order_id DESC
");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

// Group orders by order_id
$orders = [];
while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_id' => $order_id,
            'buyer_name' => $row['buyer_name'],
            'status' => $row['status'],
            'total_price' => $row['total_price'],
            'address' => $row['address'],
            'phone_number' => $row['phone_number'],
            'items' => []
        ];
    }
    $orders[$order_id]['items'][] = [
        'product_id' => $row['product_id'],
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
    body {
        font-family: "SF Pro Display", "SF Pro Icons", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
        background-color: #000;
        color: white;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        height: 100vh;
    }

    h1 {
        font-size: 3rem;
        font-weight: 700;
        color: white;
        margin-top: 30px;
        text-align: center;
    }

    /* Back to Home Button */
    a {
        position: absolute;
        top: 20px;
        left: 20px;
        color: white;
        text-decoration: none;
        font-size: 1rem;
        padding: 10px 20px;
        background-color: black;
        border: 1px solid white;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    a:hover, a:active {
        background-color: white;
        color: black;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .order {
        width: 90%;
        max-width: 800px;
        background-color: #1e1e1e;
        border-radius: 10px;
        border: 1px solid #444;
        margin: 20px 0;
        padding: 20px;
        color: white;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
    }

    .order-header {
        border-bottom: 1px solid #444;
        margin-bottom: 15px;
        padding-bottom: 15px;
    }

    .order-header h3 {
        font-size: 1.6rem;
        margin: 0;
    }

    .order-header p {
        margin: 5px 0;
        font-size: 1rem;
    }

    .order-item {
        background-color: #333;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }

    .order-item p {
        margin: 5px 0;
    }

    /* Mark as Completed Button */
    .complete-btn {
        background-color: black;
        color: white;
        border: 1px solid white;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .complete-btn:hover, .complete-btn:active {
        background-color: white;
        color: black;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .complete-btn:disabled {
        background-color: black;
        cursor: not-allowed;
        opacity: 0.5;
    }

    p {
        color: white;
        font-size: 1.1rem;
    }
</style>

</head>
<body>
    <h1>My Orders</h1>
    <a href="seller_home.php">Back to Home</a>

    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <div class="order-header">
                    <h3>Order #<?php echo $order['order_id']; ?></h3>
                    <p><strong>Buyer:</strong> <?php echo htmlspecialchars($order['buyer_name']); ?></p>
                    <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
                    <p><strong>Total:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p>
                    <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>
                </div>

                <h4>Order Items:</h4>
                <?php foreach ($order['items'] as $item): ?>
                    <div class="order-item">
                        <p><strong>Product:</strong> <?php echo htmlspecialchars($item['product_name']); ?></p>
                        <p><strong>Quantity:</strong> <?php echo $item['quantity']; ?></p>
                        <p><strong>Price:</strong> ₹<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                <?php endforeach; ?>

                <!-- Mark as Completed Button -->
                <?php if ($order['status'] === 'pending'): ?>
                    <form method="POST" action="update_order_status.php">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" class="complete-btn">Mark as Completed</button>
                    </form>
                <?php else: ?>
                    <p style="color: white;"><strong>Order Completed</strong></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No orders found for your products.</p>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
