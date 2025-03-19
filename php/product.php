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



// Simulate a logged-in user
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// Initialize success message variables
$review_success_message = "";
$cart_success_message = "";

// Handle Add to Cart request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_to_cart'])) {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if (!empty($product_id) && $quantity > 0) {
        // Fetch product price
        $sql_price = "SELECT price FROM products WHERE id = ?";
        $stmt_price = $conn->prepare($sql_price);

        if ($stmt_price) {
            $stmt_price->bind_param("i", $product_id);
            $stmt_price->execute();
            $result_price = $stmt_price->get_result();

            if ($result_price->num_rows > 0) {
                $product = $result_price->fetch_assoc();
                $product_price = $product['price'];

                // Calculate total amount
                $total_amount = $product_price * $quantity;

                // Check if the product already exists in the orders table
                $sql_check = "SELECT id FROM orders WHERE user_id = ? AND product_id = ?";
                $stmt_check = $conn->prepare($sql_check);

                if ($stmt_check) {
                    $stmt_check->bind_param("ii", $user_id, $product_id);
                    $stmt_check->execute();
                    $result_check = $stmt_check->get_result();

                    if ($result_check->num_rows > 0) {
                        // Update quantity and total_amount if product exists
                        $sql_update = "UPDATE orders SET quantity = quantity + ?, total_amount = total_amount + ? WHERE user_id = ? AND product_id = ?";
                        $stmt_update = $conn->prepare($sql_update);

                        if ($stmt_update) {
                            $stmt_update->bind_param("idii", $quantity, $total_amount, $user_id, $product_id);
                            if ($stmt_update->execute()) {
                                $cart_success_message = "Product quantity updated successfully!";
                            } else {
                                echo "Error updating quantity: " . $stmt_update->error . "<br>";
                            }
                            $stmt_update->close();
                        }
                    } else {
                        // Insert new product into the cart
                        $sql_insert = "INSERT INTO orders (user_id, product_id, quantity, total_amount, status) VALUES (?, ?, ?, ?, 'Pending')";
                        $stmt_insert = $conn->prepare($sql_insert);

                        if ($stmt_insert) {
                            $stmt_insert->bind_param("iiid", $user_id, $product_id, $quantity, $total_amount);
                            if ($stmt_insert->execute()) {
                                $cart_success_message = "Product added to cart successfully!";
                            } else {
                                echo "Error inserting product: " . $stmt_insert->error . "<br>";
                            }
                            $stmt_insert->close();
                        }
                    }
                    $stmt_check->close();
                } else {
                    echo "Error preparing check query: " . $conn->error . "<br>";
                }
            } else {
                echo "Product not found.<br>";
            }
            $stmt_price->close();
        } else {
            echo "Error preparing price query: " . $conn->error . "<br>";
        }
    } else {
        $cart_success_message = "Invalid product or quantity.";
    }
}


// Handle Submit Review request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_review'])) {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    if (!empty($product_id) && !empty($user_id) && !empty($rating) && !empty($comment)) {
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);
            if ($stmt->execute()) {
                $review_success_message = "Your review has been submitted successfully!";
            }
            $stmt->close();
        } else {
            $review_success_message = "Error in submitting the review.";
        }
    } else {
        $review_success_message = "Error: All fields are required!";
    }
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- Success Messages -->
    <?php if (!empty($cart_success_message)): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($cart_success_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($review_success_message)): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($review_success_message); ?>
        </div>
    <?php endif; ?>

    <h1 class="page-title">Available Products</h1>
    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="image-wrapper">
                        <?php
                        $imagePath = $product['image'];
                        if (!file_exists($imagePath) || empty($product['image'])) {
                            $imagePath = "../uploads/placeholder.jpg";
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                    </div>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="price">Price: $<?php echo number_format($product['price'], 2); ?></p>

                    <!-- Add to Cart Form -->
                    <form method="POST" action="" class="form-inline">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <label for="quantity_<?php echo $product['id']; ?>">Quantity:</label>
                        <input type="number" id="quantity_<?php echo $product['id']; ?>" name="quantity" value="1" min="1" required>
                        <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                    </form>

                    <!-- Review Form -->
                    <div class="form-section">
                        <h4>Write a Review</h4>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <label for="rating">Rating:</label>
                            <select name="rating" required>
                                <option value="">Select Rating</option>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Good</option>
                                <option value="3">3 - Average</option>
                                <option value="2">2 - Poor</option>
                                <option value="1">1 - Terrible</option>
                            </select>
                            <label for="comment">Comment:</label>
                            <textarea name="comment" rows="3" placeholder="Write your review..." required></textarea>
                            <button type="submit" name="submit_review" class="btn">Submit Review</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>

    <?php include('../footer.php'); ?>

    <script>
        // Auto-hide success messages
        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = document.querySelectorAll('.success-message');
            successMessage.forEach((msg) => {
                setTimeout(() => {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }, 3000);
            });
        });
    </script>
</body>
</html>
