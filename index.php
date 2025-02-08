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
        background-color: #f0f4f8;
        color: #343a40;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .product-card {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        position: relative;
        margin-bottom: 20px;
        padding: 20px;
        /* Added padding for better spacing */
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .product-card img {
        transition: transform 0.3s ease;
        border-radius: 10px;
        /* Rounded corners for images */
    }

    .product-card:hover img {
        transform: scale(1.05);
    }

    .feature-box {
        background-color: #ffeeba;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        font-weight: 500;
        /* Bold text for features */
    }

    .feature-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .add-to-cart-btn {
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-size: 1.1rem;
        color: white;
        transition: background 0.3s ease, transform 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        margin-top: 10px;
        /* Added margin for spacing */
    }

    .add-to-cart-btn:hover {
        background: linear-gradient(90deg, #fbbf24, #f59e0b);
        transform: scale(1.05);
    }

    .footer {
        background: #343a40;
        color: #ffffff;
        padding: 40px 0;
    }

    .footer a {
        color: #adb5bd;
        transition: color 0.3s ease;
    }

    .footer a:hover {
        color: #ffffff;
    }

    .quantity-btn {
        background-color: #e9ecef;
        border: none;
        border-radius: 5px;
        padding: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-size: 1.2rem;
        /* Increased font size for buttons */
    }

    .quantity-btn:hover {
        background-color: #dee2e6;
    }

    /* Animation for the product cards */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-card {
        animation: fadeIn 0.5s ease forwards;
    }

    /* Additional styles for better mobile experience */
    @media (max-width: 640px) {
        .product-card {
            padding: 15px;
            /* Adjust padding for smaller screens */
        }

        .add-to-cart-btn {
            font-size: 1rem;
            /* Smaller font size for buttons on mobile */
        }

        .quantity-btn {
            font-size: 1rem;
            /* Smaller font size for quantity buttons */
        }
    }
    </style>
</head>

<body>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($products as $product) : ?>
            <div class="product-card">
                <img alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover rounded-lg"
                    src="<?php echo htmlspecialchars($product['image']); ?>" />
                <h1 class="text-xl font-bold mt-4"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="flex space-x-2 mt-2">
                    <div class="feature-box">Attract-people</div>
                    <div class="feature-box">LuckyCharm</div>
                </div>
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
                        class="text-2xl font-bold text-red-600">₹<?php echo number_format($product['price'], 2); ?></span>
                </div>
                <div class="mt-4">
                    <h2 class="text-lg font-semibold">Product Description</h2>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                </div>
                <div class="mt-4">
                    <h2 class="text-lg font-semibold">Benefits</h2>
                    <ul class="list-disc list-inside">
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
                        <button class="quantity-btn">-</button>
                        <input class="w-12 text-center border border-gray-300 rounded" type="text" value="1" />
                        <button class="quantity-btn">+</button>
                    </div>
                </div>
                <button class="add-to-cart-btn w-full">ADD TO CART</button>
            </div>
            <?php endforeach; ?>
        </div>
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