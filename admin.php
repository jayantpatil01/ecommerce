<?php
// Include the database connection file
include('db.php');
session_start();

// Check if the user is an admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Define available order statuses
$order_statuses = ['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled'];

// Handle status filter
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Handle search query
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Fetch orders from the database with optional filtering
$sql = "SELECT * FROM orders WHERE 1=1";
if ($status_filter && in_array($status_filter, $order_statuses)) {
    $sql .= " AND status = '$status_filter'";
}
if ($search_query) {
    $sql .= " AND (fullname LIKE '%$search_query%' OR id = '$search_query')";
}
$result = mysqli_query($conn, $sql);

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);

    // Update the order status in the database
    $update_sql = "UPDATE orders SET status = '$new_status' WHERE id = '$order_id'";

    if (mysqli_query($conn, $update_sql)) {
        $message = "Order status updated successfully.";
    } else {
        $message = "Error updating order status: " . mysqli_error($conn);
    }

    // Refresh the page to reflect changes
    header("Location: admin.php?status=$status_filter&search=$search_query&message=" . urlencode($message));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Manage Orders</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    /* General Styles */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 20px;
        color: #333;
        line-height: 1.6;
    }

    h1 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 30px;
        font-size: 2.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: linear-gradient(90deg, #3498db, #8e44ad);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Form Styles */
    form {
        background-color: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        transition: box-shadow 0.3s ease;
    }

    form:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    label {
        font-weight: 600;
        margin-right: 10px;
        color: #555;
    }

    select,
    input[type="text"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        width: 100%;
        max-width: 300px;
        transition: border-color 0.3s ease;
    }

    select:focus,
    input[type="text"]:focus {
        border-color: #3498db;
        outline: none;
    }

    button {
        padding: 10px 20px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 30px;
    }

    th,
    td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background-color: #3498db;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    tr:hover {
        background-color: #f9f9f9;
        transition: background-color 0.3s ease;
    }

    /* Status Update Form */
    td form {
        margin: 0;
    }

    td select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    td select:focus {
        border-color: #3498db;
        outline: none;
    }

    /* Message Styles */
    p {
        padding: 15px;
        background-color: #dff0d8;
        color: #3c763d;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 20px;
        font-weight: 600;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        form {
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        select,
        input[type="text"],
        button {
            width: 100%;
            max-width: none;
            margin-bottom: 10px;
        }

        table,
        th,
        td {
            font-size: 14px;
        }

        h1 {
            font-size: 2rem;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    table tbody tr {
        animation: fadeIn 0.5s ease-in-out;
    }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <h1>Order Management</h1>
    <?php if (isset($_GET['message'])): ?>
    <p><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>
    <form method="GET">
        <label for="status">Filter by Status:</label>
        <select name="status" id="status" onchange="this.form.submit()">
            <option value="">All</option>
            <?php foreach ($order_statuses as $status): ?>
            <option value="<?php echo $status; ?>" <?php if ($status == $status_filter) echo 'selected'; ?>>
                <?php echo $status; ?>
            </option>
            <?php endforeach; ?>
        </select>
        <label for="search">Search by Customer Name or Order ID:</label>
        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product ID</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['fullname']; ?></td>
                <td><?php echo $order['product_id']; ?></td>
                <td><?php echo $order['total_amount']; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="new_status" onchange="this.form.submit()">
                            <?php foreach ($order_statuses as $status): ?>
                            <option value="<?php echo $status; ?>"
                                <?php if ($status == $order['status']) echo 'selected'; ?>>
                                <?php echo $status; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>