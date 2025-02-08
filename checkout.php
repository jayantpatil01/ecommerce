<?php
include('db.php');

// Validate if product_id exists and is a number
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);

// Use Prepared Statement to prevent SQL Injection
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Check if product exists
if (!$product) {
    die("Product not found.");
}

// Define total price
$total_price = $product['price'] + 50;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
    body {
        font-family: "Roboto", sans-serif;
    }
    </style>
</head>

<body class="bg-white text-gray-800">
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Checkout</h1>
        <div class="flex flex-col lg:flex-row">
            <div class="lg:w-3/4">
                <div class="border rounded-lg shadow-lg p-4 mb-4">
                    <h2 class="text-lg font-semibold mb-4">Shipping Address</h2>
                    <form id="checkoutForm" method="POST" action="place_order.php">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="total_amount" value="<?php echo $total_price; ?>">
                        <input type="hidden" id="razorpay_payment_id" name="razorpay_payment_id">

                        <div class="mb-4">
                            <label class="block text-gray-700">Full Name</label>
                            <input class="w-full border border-gray-300 px-3 py-2 rounded-lg" type="text"
                                name="full_name" required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Address</label>
                            <input class="w-full border border-gray-300 px-3 py-2 rounded-lg" type="text" name="address"
                                required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">City</label>
                            <input class="w-full border border-gray-300 px-3 py-2 rounded-lg" type="text" name="city"
                                required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">State</label>
                            <input class="w-full border border-gray-300 px-3 py-2 rounded-lg" type="text" name="state"
                                required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Zip Code</label>
                            <input class="w-full border border-gray-300 px-3 py-2 rounded-lg" type="text"
                                name="zip_code" required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Phone Number</label>
                            <input class="w-full border border-gray-300 px-3 py-2 rounded-lg" type="text" name="phone"
                                required />
                        </div>

                        <button type="button" id="pay-btn"
                            class="w-full bg-yellow-500 text-white text-lg font-semibold py-3 rounded-lg shadow-lg">
                            Pay with Razorpay
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:w-1/4 lg:pl-8 mt-8 lg:mt-0">
                <div class="border rounded-lg shadow-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span> ₹ <?php echo $product['price']; ?> </span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping</span>
                        <span> ₹50 </span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Tax</span>
                        <span> ₹0 </span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mb-4">
                        <span>Total</span>
                        <span> ₹ <?php echo $total_price; ?> </span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    document.getElementById('pay-btn').onclick = function(e) {
        var options = {
            "key": "rzp_test_yBMG9VjGc9pVh3", // Replace with Razorpay key
            "amount": <?php echo $total_price * 100; ?>, // Amount in paisa
            "currency": "INR",
            "name": "CrystalCart",
            "description": "Payment for Order",
            "handler": function(response) {
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('checkoutForm').submit();
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
        e.preventDefault();
    };
    </script>
</body>

</html>