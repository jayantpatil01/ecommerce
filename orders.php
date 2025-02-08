<?php
require('db.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    echo "<div class='text-center mt-10 text-red-500'>Please log in to view your orders.</div>";
    exit();
}

$full_name = mysqli_real_escape_string($conn, $_SESSION['full_name']);

// Fetch orders for the logged-in user
$order_sql = "SELECT id, product_id, total_amount, status FROM orders WHERE fullname = '$full_name'";
$order_result = mysqli_query($conn, $order_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <!-- Back Button -->
        <div class="mb-4 text-center">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">
                <button class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Back to Home</button>
            </a>
        </div>

        <h2 class="text-2xl font-semibold mb-6">My Orders</h2>
        <?php
        if (mysqli_num_rows($order_result) > 0) {
            while ($order_row = mysqli_fetch_assoc($order_result)) {
                $product_id = $order_row['product_id'];

                // Fetch product details for the current order
                $product_sql = "SELECT name, price, image FROM products WHERE id = '$product_id'";
                $product_result = mysqli_query($conn, $product_sql);

                if ($product_row = mysqli_fetch_assoc($product_result)) {
                    echo "<div class='bg-white shadow-md rounded-lg p-6 mb-6'>";
                    echo "<div class='flex items-center'>";
                    if (!empty($product_row['image'])) {
                        echo "<img src='" . htmlspecialchars($product_row['image']) . "' alt='Product Image' class='w-24 h-24 object-cover rounded mr-6'>";
                    }
                    echo "<div>";
                    echo "<p class='text-gray-600'><strong>Product:</strong> " . htmlspecialchars($product_row['name']) . "</p>";
                    echo "<p class='text-gray-600'><strong>Price:</strong> ₹" . htmlspecialchars($product_row['price']) . "</p>";
                    echo "<p class='text-gray-600'><strong>Total Amount:</strong> ₹" . htmlspecialchars($order_row['total_amount']) . "</p>";
                    echo "<p class='text-gray-600'><strong>Status:</strong> " . htmlspecialchars($order_row['status']) . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<p class='text-red-500'>Product details not found for order ID: " . htmlspecialchars($order_row['id']) . "</p>";
                }
            }
        } else {
            echo "<p class='text-gray-500'>No orders found.</p>";
        }
        ?>
    </div>
</body>

</html>