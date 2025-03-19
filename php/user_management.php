<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';
// Start the session

// Check if the user is logged in and if the role is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html"); // Redirect to login page if not logged in or not admin
    exit();
}

// Include database connection (Correct path)

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Error handling for SQL
if (!$result) {
    echo "Error executing query: " . $conn->error;
    exit();
}

// Fetch the logged-in admin's information (if needed)
$admin_id = $_SESSION['user_id'];
$admin_username = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Organic Food Store</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Correct path to admin.css -->
</head>
<body>
    <!-- Admin Header Section -->
    <header class="admin-header">
        <div class="admin-logo">
            <img src="../image/logo.jpeg" alt="Organic Food Logo"> <!-- Correct path to logo -->
        </div>
        <div class="admin-welcome">
            <h1>Welcome, Admin</h1>
            <p>Manage users of Organic Food Store</p>
        </div>
    </header>

    <!-- Admin Dashboard Section -->
    <section class="admin-dashboard">
        <h2>Users List</h2>
        <a href="add_user.php" class="admin-btn">Add New User</a>

        <!-- Table to Display Users -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="admin-btn">Edit</a>
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="admin-btn delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- Footer Section -->
    <footer class="footer-container">
        <p>&copy; 2023 Organic Food Store. All rights reserved.</p>
    </footer>

    <script src="../js/main.js"></script> <!-- Correct path to main.js -->
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
