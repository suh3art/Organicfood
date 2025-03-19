<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "organic";



// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Fetch form data and sanitize inputs
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Validate required fields
    if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
        // Redirect back with an error parameter if any field is empty
        header("Location: products.php?error=1");
        exit();
    }

    // Prepare SQL to insert review
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error in SQL statement preparation: " . $conn->error);
    }

    $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);

    // Execute the query and handle the result
    if ($stmt->execute()) {
        // Redirect back to products.php with success parameter
        header("Location: http://localhost/organic/php/products.php?success=1");
        exit();
    } else {
        // Redirect back to products.php with an error message if the query fails
        header("Location: products.php?error=2");
        exit();
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
