<?php
// Database connection
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate that the passwords match
    if ($password !== $confirm_password) {
        header("Location: ../register.html?status=error&message=Passwords+do+not+match");
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $sql_check = "SELECT id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        header("Location: ../register.html?status=error&message=Email+already+exists");
        $stmt_check->close();
        exit;
    }
    $stmt_check->close();

    // Insert the new user into the database
    $sql_insert = "INSERT INTO users (firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sssss", $firstname, $lastname, $email, $phone, $hashed_password);

    if ($stmt_insert->execute()) {
        header("Location: ../register.html?status=success&message=Registration+successful,+Welcome+" . urlencode($firstname));
    } else {
        header("Location: ../register.html?status=error&message=Failed+to+register");
    }

    $stmt_insert->close();
    $conn->close();
} else {
    header("Location: ../register.html?status=error&message=Invalid+request");
}
?>
