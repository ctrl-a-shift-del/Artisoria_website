<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        /* General Styling */
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #000;
            color: white;
            margin: 0;
            padding: 0;
            min-height: 100vh; /* Ensure full height for the body */
            display: flex;
            flex-direction: column;
        }

        /* Header Styling */
        header {
            background-color: #000000;
            padding: 10px;
            text-align: center;
            border-bottom: 2px solid #000000;
            flex-shrink: 0; /* Prevent header from shrinking */
        }

        header h1 {
            font-size: 2.5rem;
            margin: 0;
            display: none; /* Hide the header text */
        }

        /* Navigation Button */
        .back-button {
            display: inline-block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            border: 2px solid white;
            padding: 10px;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: block;
            text-align: center;
            width: 125px;
            margin: 0 0px;
        }

        .back-button:hover {
            background-color: white;
            color: black;
        }

        /* Form Section */
        .form-container {
            padding: 20px;
            max-width: 700px;
            margin: 40px auto;
            background-color: #111;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
            border: 1px solid #fff;
            box-sizing: border-box;
            flex-grow: 1; /* Allow the form container to grow */
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            margin-top: 0px;
        }

        label {
            font-size: 1rem;
            font-weight: bold;
            display: block;
            margin-top: 12px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 2px solid white;
            background: black;
            color: white;
            font-size: 1rem;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="file"] {
            border: none;
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
            .form-container {
                padding: 15px;
                max-width: 90%;
            }

            h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

    <header>
        <!-- Removed "Seller Dashboard" text -->
        <a href="my_products.php" class="back-button">Back to My products</a>
    </header>

    <div class="form-container">
        <h2>Add a New Product</h2>

        <form action="add_product_process.php" method="post" enctype="multipart/form-data">
            <label>Product Name:</label>
            <input type="text" name="name" required>

            <label>Price:</label>
            <input type="number" name="price" step="0.01" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <label>Upload Images (Max 10):</label>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <label>Image <?php echo $i; ?>:</label>
                <input type="file" name="image_<?php echo $i; ?>" accept="image/*">
            <?php endfor; ?>

            <button type="submit">Add Product</button>
        </form>
    </div>

</body>
</html>
