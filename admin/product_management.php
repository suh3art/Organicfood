<?php
// Start session
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

// Include database connection
require '../db_connection.php';

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Organic Food Store</title>
    <link rel="stylesheet" href="../admin/admin.css"> <!-- Path to admin.css -->
</head>
<body>
    <header class="admin-header">
        <div class="admin-logo">
            <img src="../image/logo.jpeg" alt="Organic Food Logo"> <!-- Correct path to logo -->
        </div>
        <div class="admin-welcome">
            <h1>Product Management</h1>
            <p>Manage products of Organic Food Store</p>
        </div>
    </header>

    <section class="admin-dashboard">
        <h2>Products List</h2>

        <!-- Add Product Button -->
        <a href="add_product.php" class="admin-btn">Add New Product</a>

        <!-- Table to Display Products -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['stock_quantity']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="admin-btn">Edit</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="admin-btn delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <footer class="footer-container">
        <p>&copy; 2023 Organic Food Store. All rights reserved.</p>
    </footer>

    <script src="../js/main.js"></script> <!-- Path to main.js -->
</body>
</html>

<?php
$conn->close();
?>
