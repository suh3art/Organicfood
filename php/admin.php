<?php
// Start the session
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Organic Food Store</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Correct path to admin.css -->
</head>
<body>
    <header class="admin-header">
        <div class="admin-logo">
            <img src="../image/logo.jpeg" alt="Organic Food Logo"> <!-- Correct path to logo -->
        </div>
        <div class="admin-welcome">
            <h1>Welcome, Admin</h1>
            <p>Manage users, products, and more</p>
        </div>
    </header>

    <section class="admin-dashboard">
        <h2>Admin Dashboard</h2>
        <div class="dashboard-links">
            <a href="user_management.php" class="admin-btn">User Management</a>
            <a href="product_management.php" class="admin-btn">Product Management</a>
            <a href="logout.php" class="admin-btn logout-btn">Logout</a>
        </div>
    </section>

    <footer class="footer-container">
        <p>&copy; 2023 Organic Food Store. All rights reserved.</p>
    </footer>

    <script src="../js/main.js"></script> <!-- Correct path to main.js -->
</body>
</html>
