<?php
session_start(); // Start the session

// Include database connection
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input values from the form
    $full_name = trim($_POST['full_name']);
    $phone_number = trim($_POST['phone_number']);

    // Validate input (check if fields are not empty and phone number is numeric)
    if (empty($full_name) || empty($phone_number)) {
        $error = "Both fields are required.";
    } elseif (!is_numeric($phone_number)) {
        $error = "Phone number must be numeric.";
    } else {
        // Query to check if the provided full name and phone number exist in the database
        $sql = "SELECT * FROM orders WHERE fullname = ? AND phone = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $full_name, $phone_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Valid user, store full name in session and redirect to the product page
            $_SESSION['full_name'] = $full_name;
            header("Location: orders.php"); // Redirect to products page
            exit;
        } else {
            $error = "Invalid full name or phone number.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Crystal Cart - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-white text-gray-800">
    <!-- Login Form -->
    <div class="max-w-sm mx-auto mt-16 p-6 border border-gray-300 rounded-lg shadow-lg bg-white">
        <h2 class="text-2xl font-bold text-center mb-4">Login</h2>

        <?php if (isset($error)) : ?>
        <div class="text-red-500 text-sm text-center mb-4">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-4">
                <label for="full_name" class="block text-sm font-semibold">Full Name</label>
                <input type="text" id="full_name" name="full_name"
                    class="w-full p-2 border border-gray-300 rounded-lg mt-1" placeholder="Enter your full name"
                    required />
            </div>
            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-semibold">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number"
                    class="w-full p-2 border border-gray-300 rounded-lg mt-1" placeholder="Enter your phone number"
                    required />
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Login</button>
        </form>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">
                <button class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Back to Home</button>
            </a>
        </div>
    </div>
</body>

</html>