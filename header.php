<?php
// Start the session

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organic Food Store</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="header-container">
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="about.html">About Me</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="product.php">Products</a></li>
            <li><a href="order.php">My Cart</a></li>
            <li><a href="reviews.php">Reviews</a></li>
        </ul>
        <div class="logo-container">
            <a href="home.php"><img src="../image/logo.jpeg" alt="Organic Food Logo" class="logo"></a>
        </div>
    </nav>

    <div class="user-profile">
        <?php if (isset($_SESSION['user_name'])): ?>
            <p>Welcome, <span id="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span></p>
            <a href="../php/logout.php" class="logout-btn">Logout</a> <!-- Logout link -->
        <?php else: ?>
            <p>Welcome, <span id="user-name">Guest</span></p>
            <p>Email: <span id="user-email">Not logged in</span></p>
            <a href="../index.html" class="login-btn">Login</a> <!-- Login link -->
        <?php endif; ?>
    </div>
</header>
