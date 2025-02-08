<?php 
session_start(); // Start session to manage login state
require_once 'db.php';

// Fetch products from the database including description and benefits
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Crystal Cart - Product Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .footer {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
    }

    .footer a {
        color: #bdc3c7;
        transition: color 0.3s ease;
    }

    .footer a:hover {
        color: #ecf0f1;
    }

    .quantity-btn {
        transition: background-color 0.3s ease;
    }

    .quantity-btn:hover {
        background-color: #e2e8f0;
    }

    .add-to-cart-btn {
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .add-to-cart-btn:hover {
        background-color: #f59e0b;
        transform: scale(1.05);
    }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow-sm p-4">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <div class="text-2xl font-bold text-gray-800">
                Crystal Cart
            </div>

            <!-- Header Links (My Cart, My Orders, Login/Logout) -->
            <div class="flex space-x-4 items-center">
                <a href="cart.php" class="flex items-center text-gray-800 hover:text-yellow-500">
                    <i class="fas fa-shopping-cart mr-2"></i> My Cart
                </a>
                <a href="orders.php" class="text-gray-800 hover:text-yellow-500">
                    My Orders
                </a>

                <?php if (isset($_SESSION['user_name'])) : ?>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user text-gray-800"></i>
                    <span class="text-gray-800 font-semibold"><?php echo $_SESSION['user_name']; ?></span>
                    <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600">
                        Logout
                    </a>
                </div>
                <?php else : ?>
                <a href="login.php"
                    class="flex items-center bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">
                    <i class="fas fa-user mr-2"></i> Login
                </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <?php if (!empty($products)) : ?>
        <?php foreach ($products as $product) : ?>
        <div class="flex flex-col lg:flex-row mt-8 border-b pb-8 product-card">
            <div class="lg:w-1/2">
                <img alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full rounded-lg shadow-lg"
                    height="800" src="<?php echo htmlspecialchars($product['image']); ?>" width="600" />
                <div class="flex space-x-2 mt-4">
                    <img alt="<?php echo htmlspecialchars($product['name']); ?> Thumbnail"
                        class="w-20 h-20 rounded-lg shadow-lg" height="100"
                        src="<?php echo htmlspecialchars($product['Thumbnail']); ?>" width="100" />
                </div>
            </div>
            <div class="lg:w-1/2 lg:pl-8 mt-8 lg:mt-0">
                <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($product['name']); ?></h1>

                <div class="flex items-center mt-2">
                    <div class="flex items-center text-yellow-500">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="ml-2 text-gray-600"><?php echo htmlspecialchars($product['review']); ?> reviews</span>
                </div>

                <div class="mt-4">
                    <span
                        class="text-3xl font-bold text-red-600">₹<?php echo number_format($product['price'], 2); ?></span>
                </div>

                <!-- Dynamic Description Section -->
                <div class="mt-6 text-gray-700">
                    <h2 class="text-xl font-semibold">Product Description</h2>
                    <p class="mt-2">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>
                </div>

                <!-- Dynamic Benefits Section -->
                <div class="mt-6 text-gray-700">
                    <h2 class="text-xl font-semibold">Benefits</h2>
                    <ul class="list-disc list-inside mt-2">
                        <?php 
                            $benefits = explode("\n", $product['benfits']);
                            foreach ($benefits as $benefit) {
                                echo "<li>" . htmlspecialchars($benefit) . "</li>";
                            }
                        ?>
                    </ul>
                </div>

                <div class="mt-4">
                    <h2 class="text-lg font-semibold">Quantity</h2>
                    <div class="flex items-center mt-2">
                        <button class="quantity-btn bg-gray-200 text-gray-600 px-3 py-1 rounded-l-lg">-</button>
                        <input class="w-12 text-center border-t border-b border-gray-200" type="text" value="1" />
                        <button class="quantity-btn bg-gray-200 text-gray-600 px-3 py-1 rounded-r-lg">+</button>
                    </div>
                </div>

                <div class="mt-4 text-gray-600">
                    693 orders placed in the last 24 hours
                </div>

                <button
                    class="add-to-cart-btn mt-6 w-full bg-yellow-500 text-white text-lg font-semibold py-3 rounded-lg shadow-lg"
                    id="add-to-cart-button" onclick="window.location.href='cart.php?id=<?php echo $product['id']; ?>'">
                    ADD TO CART
                </button>

            </div>
        </div>
        <?php endforeach; ?>
        <?php else : ?>
        <p class="text-center text-gray-600">No products found.</p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer py-8 mt-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Crystal Cart</h3>
                    <p class="text-gray-400">Your one-stop shop for the finest crystals and gemstones.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul>
                        <li><a href="#" class="hover:text-white">Home</a></li>
                        <li><a href="#" class="hover:text-white">Products</a></li>
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-8 text-gray-400">
                &copy; 2023 Crystal Cart. All rights reserved.
            </div>
        </div>
    </footer>
</body>

</html>