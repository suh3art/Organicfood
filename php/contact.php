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

// Initialize success message and error message
$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : "";
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : "";
    $message = isset($_POST['message']) ? trim($_POST['message']) : "";

    // Validate required fields
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Insert contact submission into the database
            $sql = "INSERT INTO contact_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssss", $name, $email, $subject, $message);
                if ($stmt->execute()) {
                    $success_message = "Your message has been submitted successfully!";

                    // Send confirmation email
                    $to = $email;
                    $subject = "Thank you for contacting us";
                    $body = "Hi $name,\n\nThank you for reaching out! We have received your message:\n\n"
                          . "\"$message\"\n\nWe will get back to you shortly.\n\nBest regards,\nOrganic Food Team";
                    $headers = "From: no-reply@organicfood.com";

                    if (mail($to, $subject, $body, $headers)) {
                        $success_message .= " A confirmation email has been sent to your email address.";
                    } else {
                        $error_message = "Failed to send the confirmation email.";
                    }
                } else {
                    $error_message = "Error submitting your message: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_message = "Error preparing the statement: " . $conn->error;
            }
        } else {
            $error_message = "Please enter a valid email address.";
        }
    } else {
        $error_message = "All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="page-title">Contact Us</h1>
    <div class="container">
        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="contact-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
    <?php include('../footer.php'); ?>
</body>
</html>
