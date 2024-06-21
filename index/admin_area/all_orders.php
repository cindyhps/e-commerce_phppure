<?php
// Attempt to include file koneksi database
$connect_path = '../includes/connect.php';
    
if (file_exists($connect_path)) {
    include($connect_path);
} else {
    die("Include file '{$connect_path}' not found or accessible.");
}

session_start();

// Check if $con is defined and valid
if (!isset($con) || !$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve all orders with user information from the database
$select_orders_query = "
    SELECT orders.*, users.username, users.email
    FROM orders
    LEFT JOIN users ON orders.user_id = users.user_id
    ORDER BY order_date DESC
";
$result_orders = mysqli_query($con, $select_orders_query);

if (!$result_orders) {
    die("Query failed: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>All Orders</h1>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Username</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result_orders)) {
                echo "<tr>";
                echo "<td>{$row['session_id']}</td>";
                echo "<td>{$row['product_id']}</td>";
                echo "<td>{$row['quantity']}</td>";
                echo "<td>{$row['product_price']}</td>";
                // Format tanggal menjadi "YYYY-MM-DD" untuk "DD-MM-YYYY"
                $formatted_date = date('d-m-Y', strtotime($row['order_date']));
                echo "<td>{$formatted_date}</td>";
                echo "<td>{$row['status']}</td>";
                echo "<td>{$row['username']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close database connection when done
mysqli_close($con);
?>
