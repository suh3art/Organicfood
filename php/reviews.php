<?php
session_start();
include('../header.php');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "organic";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all reviews with the reviewer's name and product image
$sql = "SELECT reviews.id, reviews.rating, reviews.comment, reviews.created_at, 
               products.name AS product_name, products.image AS product_image, 
               users.firstname, users.lastname
        FROM reviews
        INNER JOIN products ON reviews.product_id = products.id
        INNER JOIN users ON reviews.user_id = users.id
        ORDER BY reviews.created_at DESC";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reviews</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="page-title">All Reviews</h1>
    <div class="reviews-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($review = $result->fetch_assoc()): ?>
                <div class="review-card">
                    <div class="review-header">
                        <img src="<?php echo htmlspecialchars($review['product_image']); ?>" alt="<?php echo htmlspecialchars($review['product_name']); ?>" class="product-thumbnail">
                        <h3 class="product-name"><?php echo htmlspecialchars($review['product_name']); ?></h3>
                    </div>
                    <p class="reviewer-name">
                        Posted by: <?php echo htmlspecialchars($review['firstname'] . ' ' . $review['lastname']); ?>
                    </p>
                    <p class="review-rating">Rating: <?php echo str_repeat('⭐', $review['rating']); ?></p>
                    <p class="review-comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                    <p class="review-date">Posted on: <?php echo htmlspecialchars($review['created_at']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-reviews">No reviews have been posted yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include('../footer.php'); ?>
<?php
$conn->close();
?>
