<?php
session_start();
include('../header.php');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "organic";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 10 products
$product_query = "SELECT * FROM products LIMIT 10";
$product_result = $conn->query($product_query);

// Fetch 5 reviews
$review_query = "SELECT reviews.rating, reviews.comment, users.firstname, users.lastname, products.name AS product_name 
                 FROM reviews 
                 JOIN users ON reviews.user_id = users.id 
                 JOIN products ON reviews.product_id = products.id 
                 ORDER BY reviews.created_at DESC 
                 LIMIT 5";
$review_result = $conn->query($review_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Organic Food Store</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>../css/home_style.css">
</head>
<body>
<main class="home-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-text">
            <h1>Welcome to Organic Food Store</h1>
            <p>Your one-stop destination for fresh, organic, and healthy products.</p>
            <a href="product.php" class="hero-btn">Shop Now</a>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <h2>Why Choose Us?</h2>
        <p>We offer a wide range of fresh and organic products sourced directly from trusted farmers. Every purchase you make contributes to a healthier planet and supports sustainable farming.</p>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products-section">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php if ($product_result->num_rows > 0): ?>
                <?php while ($product = $product_result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <a href="products.php" class="btn">View Product</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products available.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Top Reviews Section -->
    <section class="reviews-section">
        <h2>What Our Customers Say</h2>
        <div class="reviews-grid">
            <?php if ($review_result->num_rows > 0): ?>
                <?php while ($review = $review_result->fetch_assoc()): ?>
                    <div class="review-card">
                        <h4><?php echo htmlspecialchars($review['firstname']) . ' ' . htmlspecialchars($review['lastname']); ?></h4>
                        <p class="review-product"><?php echo htmlspecialchars($review['product_name']); ?></p>
                        <p class="review-comment">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                        <p class="review-rating">Rating: <?php echo str_repeat('⭐', $review['rating']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No reviews available.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Additional Information Section -->
    <section class="extra-section">
        <h2>Discover Our Philosophy</h2>
        <p>At Organic Food Store, we believe in delivering more than just products. We are committed to providing an experience rooted in trust, quality, and sustainability. Join us on our journey towards a healthier world.</p>
    </section>
</main>

<?php include('../footer.php'); ?>
</body>
</html>
