<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require 'db_connection.php';

// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input
    $username_or_email = trim($_POST['username_or_email']);
    $password = trim($_POST['password']);

    // Check if input is an email or username
    if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
        // It's an email
        $sql = "SELECT id, username, password, role FROM users WHERE email = ?";
    } else {
        // It's a username
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_or_email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $username;

            $_SESSION['role'] = $role;

            // Redirect based on user role
            if ($role == 'admin') {
                // Admin role, redirect to admin page
                header("Location: admin.php"); 
                exit();
            } else {
                // Regular user, redirect to home page
                header("Location: home.php");
                exit();
            }
        } else {
            header("Location: index.html?status=error&message=Invalid+password");
            exit();
        }
    } else {
        header("Location: index.html?status=error&message=User+not+found");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.html?status=error&message=Invalid+request");
    exit();
}
