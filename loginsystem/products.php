<?php
session_start();
include_once('includes/config.php');

// Ensure $_SESSION['id'] is set and not empty
if (empty($_SESSION['id'])) {
    header('location: logout.php');
    exit(); // Stop further execution if session is not valid
}

// Handle product upload
if (isset($_POST['upload'])) {
    $productName = mysqli_real_escape_string($con, $_POST['product_name']);
    $productPrice = mysqli_real_escape_string($con, $_POST['product_price']);
    $productSize = mysqli_real_escape_string($con, $_POST['product_size']);
    $productQuantity = mysqli_real_escape_string($con, $_POST['product_quantity']);
    $productImage = $_FILES['product_image']['name'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($productImage);

    // Check if uploads directory exists, if not create it
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Move uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
        $userid = $_SESSION['id'];
        $query = "INSERT INTO products (user_id, product_name, product_price, product_size, product_quantity, product_image) 
                  VALUES ('$userid', '$productName', '$productPrice', '$productSize', '$productQuantity', '$targetFile')";
        
        // Execute query and handle errors
        if (!mysqli_query($con, $query)) {
            echo "<script>alert('Failed to upload product: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload image');</script>";
    }
}

// Handle product deletion
if (isset($_POST['delete'])) {
    $productId = mysqli_real_escape_string($con, $_POST['product_id']);

    // Fetch product details to get file path for deletion
    $productQuery = mysqli_query($con, "SELECT product_image FROM products WHERE id='$productId'");
    
    if ($productQuery) {
        $product = mysqli_fetch_assoc($productQuery);
        $productImage = $product['product_image'];

        // Delete product from database
        $deleteQuery = mysqli_query($con, "DELETE FROM products WHERE id='$productId'");

        if (!$deleteQuery) {
            echo "<script>alert('Failed to delete product: " . mysqli_error($con) . "');</script>";
        } else {
            // Delete product image from uploads directory
            if (!empty($productImage) && file_exists($productImage)) {
                unlink($productImage); // Delete the file from server
            }
        }
    } else {
        echo "<script>alert('Failed to fetch product details');</script>";
    }
}

// Fetch user products
$userid = $_SESSION['id'];
$productQuery = mysqli_query($con, "SELECT * FROM products WHERE user_id='$userid'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container {
            background-color: #f0f0f0;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-top: 0;
            text-align: center;
        }
        fieldset {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        legend {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: calc(100% - 10px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            padding: 6px;
        }
        .file-upload-text {
            margin-top: 5px;
            display: inline-block;
            font-size: 14px;
            color: #666;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        button:active {
            background-color: #0056b3;
            transform: translateY(1px);
        }
        .product-list {
            list-style-type: none;
            padding: 0;
        }
        .product-list li {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            overflow: hidden;
            border-radius: 6px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product-list li img {
            float: left;
            margin-right: 10px;
            border-radius: 4px;
        }
        .product-info {
            float: left;
        }
        .product-info strong {
            font-size: 1.2em;
            display: block;
            margin-bottom: 5px;
        }
        .product-info span {
            display: block;
            margin-bottom: 5px;
        }
        .delete-form {
            float: right;
            margin-top: 10px;
        }
        .delete-form button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to your Product Page</h1>

        <section class="form-container">
            <h2>Add a New Product</h2>
            <form method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Product Details</legend>
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" name="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="product_price">Product Price:</label>
                        <input type="number" id="product_price" name="product_price" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="product_size">Product Size:</label>
                        <input type="text" id="product_size" name="product_size" required>
                    </div>
                    <div class="form-group">
                        <label for="product_quantity">Quantity Available:</label>
                        <input type="number" id="product_quantity" name="product_quantity" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="product_image">Product Image:</label>
                        <input type="file" id="product_image" name="product_image" required>
                        <span class="file-upload-text">No file chosen</span>
                    </div>
                </fieldset>
                <div class="form-group">
                    <button type="submit" name="upload">Upload</button>
                </div>
            </form>
        </section>

        <h2>Your Products</h2>
        <ul class="product-list">
            <?php while ($product = mysqli_fetch_assoc($productQuery)): ?>
                <li>
                    <img src="<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="100">
                    <div class="product-info">
                        <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
                        <span>Price: ₦<?php echo htmlspecialchars($product['product_price']); ?></span>
                        <span>Size: <?php echo htmlspecialchars($product['product_size']); ?></span>
                        <span>Quantity: <?php echo htmlspecialchars($product['product_quantity']); ?></span>
                    </div>
                    <form method="post" class="delete-form">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>