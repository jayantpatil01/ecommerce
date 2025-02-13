<?php
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['razorpay_payment_id'])) {
    $product_id = $_POST['product_id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $total_amount = $_POST['total_amount'];
    $payment_id = $_POST['razorpay_payment_id'];
    $order_date = date('Y-m-d'); // Gets the current date in YYYY-MM-DD format
    $sql = "INSERT INTO orders (product_id, fullname, address, city, state, zip_code, phone, total_amount, status, order_date ,payment_id, payment_status) 
        VALUES ('$product_id', '$full_name', '$address', '$city', '$state', '$zip_code', '$phone', '$total_amount', 'Confirmed', '$order_date', '$payment_id', 'Paid')";


    if (mysqli_query($conn, $sql)) {
        $_SESSION['full_name'] = $full_name;
        $_SESSION['product_id'] = $product_id;
        echo "<script>
            alert('Payment successful! Order placed.');
            window.location.href = 'orders.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "<script>
        alert('Payment failed! Please try again.');
        window.location.href = 'checkout.php';
    </script>";
}
?>