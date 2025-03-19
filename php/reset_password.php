<?php
// Start session
session_start();

// Include the database connection
require 'db_connection.php';

// Initialize message variables for success or error
$message = '';
$status = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $message = "Passwords do not match!";
        $status = 'error';
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            // If the password is updated successfully, redirect to the login page
            header("Location: ../index.html?status=success&message=Password+reset+successfully.");
            exit();
        } else {
            $message = "Failed to reset the password. Please try again.";
            $status = 'error';
        }

        $stmt->close();
    }
}

// If there was an error or password did not match, redirect to reset password page
if ($status === 'error') {
    header("Location: ../reset_password.html?status=$status&message=" . urlencode($message));
    exit();
}
?>
