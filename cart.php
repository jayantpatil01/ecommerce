<?php
include('db.php');

// Check if 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];  // Get the id from the URL
    $sql = "SELECT * FROM products WHERE id = $id";  // Query the database to get the product with the given id
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);  // Fetch the product data

    if ($product) {
        // Product found, display cart details
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Cart - Emerald Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-4 text-center">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">
                <button class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Back to Home</button>
            </a>
        </div>

        <h1 class="text-3xl font-semibold text-center text-gray-800 mb-8">Your Cart</h1>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div id="cartItems" class="overflow-x-auto">
                <div class="flex items-center justify-between mb-4 border-b pb-4">
                    <div class="flex items-center space-x-4">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                            class="w-20 h-20 object-cover rounded-md">
                        <div class="text-xl font-bold text-gray-800">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </div>
                    </div>
                    <div class="text-xl text-gray-600">
                        ₹<?php echo number_format($product['price'], 2); ?>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-between items-center">
                <span id="totalPrice" class="text-2xl font-semibold text-gray-800">Total:
                    ₹<?php echo number_format($product['price'], 2); ?></span>
                <button
                    class="bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out"
                    onclick="window.location.href='checkout.php?id=<?php echo $product['id']; ?>'">
                    Proceed to Checkout
                </button>
            </div>
        </div>
    </div>
</body>

</html>
<?php
    } else {
        // Product not found
        echo "<div class='text-center mt-10 text-red-500'>Product not found.</div>";
    }
} else {
    // 'id' parameter is missing
    echo "<div class='text-center mt-10 text-gray-500'>No item in cart.</div>";
}
?>