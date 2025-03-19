<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "organic";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulate a logged-in user (replace with actual session-based user management)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// Check if POST request is made
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve product_id and quantity
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Debugging: Print the received data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    if (!empty($product_id) && $quantity > 0) {
        // Check if the product already exists in the orders table for the user
        $sql_check = "SELECT id FROM orders WHERE user_id = ? AND product_id = ?";
        $stmt_check = $conn->prepare($sql_check);

        if ($stmt_check) {
            $stmt_check->bind_param("ii", $user_id, $product_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                // Update quantity if the product exists
                $sql_update = "UPDATE orders SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
                $stmt_update = $conn->prepare($sql_update);

                if ($stmt_update) {
                    $stmt_update->bind_param("iii", $quantity, $user_id, $product_id);
                    if ($stmt_update->execute()) {
                        $_SESSION['cart_message'] = "Product quantity updated in cart successfully!";
                    } else {
                        $_SESSION['cart_message'] = "Error updating quantity: " . $stmt_update->error;
                    }
                    $stmt_update->close();
                } else {
                    echo "Error preparing update statement: " . $conn->error;
                }
            } else {
                // Insert new product into cart
                $sql_insert = "INSERT INTO orders (user_id, product_id, quantity, status) VALUES (?, ?, ?, 'Pending')";
                $stmt_insert = $conn->prepare($sql_insert);

                if ($stmt_insert) {
                    $stmt_insert->bind_param("iii", $user_id, $product_id, $quantity);
                    if ($stmt_insert->execute()) {
                        $_SESSION['cart_message'] = "Product added to cart successfully!";
                    } else {
                        $_SESSION['cart_message'] = "Error adding product: " . $stmt_insert->error;
                    }
                    $stmt_insert->close();
                } else {
                    echo "Error preparing insert statement: " . $conn->error;
                }
            }
            $stmt_check->close();
        } else {
            echo "Error preparing check statement: " . $conn->error;
        }
    } else {
        $_SESSION['cart_message'] = "Invalid product or quantity.";
    }

    // Redirect back to products.php
    header("Location: products.php");
    exit();
}

$conn->close();
?>
