<?php
session_start();
include('../header.php');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "organic";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulate a logged-in user (use session-based user management in production)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// Fetch user orders
$sql = "SELECT o.id AS order_id, p.name AS product_name, p.image AS product_image, o.quantity, o.total_amount, o.status
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.user_id = ?";
$stmt = $conn->prepare($sql);

$total_quantity = 0;
$total_price = 0;

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error preparing statement: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="page-title">My Orders</h1>
    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($order['product_image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>" class="order-product-image">
                            </td>
                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                        </tr>
                        <?php
                        // Calculate total quantity and price
                        $total_quantity += $order['quantity'];
                        $total_price += $order['total_amount'];
                        ?>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th><?php echo $total_quantity; ?></th>
                        <th>$<?php echo number_format($total_price, 2); ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <?php include('../footer.php'); ?>
</body>
</html>
