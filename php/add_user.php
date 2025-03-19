<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html"); // Redirect to login page if not logged in or not admin
    exit();
}



// Define variables and initialize with empty values
$first_name = $last_name = $username = $email = $phone = $password = $role = "";
$first_name_err = $last_name_err = $username_err = $email_err = $phone_err = $password_err = $role_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter the first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter the last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate phone number
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter a phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate role
    if (empty(trim($_POST["role"]))) {
        $role_err = "Please select a role.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Check for any errors and if none, insert the data into the database
    if (empty($first_name_err) && empty($last_name_err) && empty($username_err) && empty($email_err) && empty($phone_err) && empty($password_err) && empty($role_err)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $sql = "INSERT INTO users (first_name, last_name, username, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $first_name, $last_name, $username, $email, $phone, $hashed_password, $role);

            if ($stmt->execute()) {
                header("Location: user_management.php?status=success&message=User added successfully");
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Admin Panel</title>


    <style>/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to bottom right, #d9f2d9, #ffffff);
    color: #333;
}

/* Header Section */
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #4CAF50, #2196F3);
    padding: 1rem 2rem;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.admin-logo img {
    width: 100px;
}

.admin-welcome h1 {
    color: white;
    font-size: 1.5rem;
}

.admin-welcome p {
    color: white;
    font-size: 1rem;
}

/* Admin Dashboard */
.admin-dashboard {
    padding: 2rem;
    background: white;
    margin: 20px auto;
    max-width: 800px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.admin-dashboard h2 {
    color: #4CAF50;
    font-size: 2rem;
    text-align: center;
    margin-bottom: 2rem;
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-group input,
.form-group select {
    padding: 0.8rem;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #4CAF50;
    box-shadow: 0px 0px 8px rgba(76, 175, 80, 0.5);
}

button.admin-btn {
    padding: 1rem;
    background: linear-gradient(135deg, #4CAF50, #45a049);
    color: white;
    font-size: 1.2rem;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.3s ease;
}

button.admin-btn:hover {
    background: linear-gradient(135deg, #45a049, #4CAF50);
    transform: translateY(-3px);
}

button.admin-btn:focus {
    outline: none;
}

span.error {
    color: red;
    font-size: 0.9rem;
}

/* Footer Section */
.footer-container {
    text-align: center;
    padding: 1rem;
    background: #d9f2d9;
    color: #333;
    box-shadow: 0px -4px 8px rgba(0, 0, 0, 0.2);
}

.footer-text {
    font-size: 0.9rem;
    margin-top: 1rem;
    color: #333;
}

/* Media Queries for Responsiveness */
@media screen and (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .admin-dashboard {
        width: 100%;
        margin: 10px;
        padding: 15px;
    }

    .admin-dashboard h2 {
        font-size: 1.5rem;
    }
}

        </style>
</head>
<body>
    <!-- Admin Header Section -->
    <?php include('../header.php'); ?>

    <main>
        <section class="admin-dashboard">
            <h2>Add New User</h2>
            <form action="add_user.php" method="POST">
                <!-- First Name -->
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>
                    <span class="error"><?php echo $first_name_err; ?></span>
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>
                    <span class="error"><?php echo $last_name_err; ?></span>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                    <span class="error"><?php echo $username_err; ?></span>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                    <span class="error"><?php echo $email_err; ?></span>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                    <span class="error"><?php echo $phone_err; ?></span>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error"><?php echo $password_err; ?></span>
                </div>

                <!-- Role -->
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
                    </select>
                    <span class="error"><?php echo $role_err; ?></span>
                </div>

                <button type="submit" class="admin-btn">Add User</button>
            </form>
        </section>
    </main>

    <!-- Footer Section -->
    <?php include('../footer.php'); ?>
</body>
</html>
