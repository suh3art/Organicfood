<?php
// Start session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); // Redirect to login page if not logged in or not admin
    exit();
}

// Initialize variables for product form
$name = $description = $price = $stock_quantity = $category = "";
$name_err = $description_err = $price_err = $stock_quantity_err = $category_err = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter the product name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate description
    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter the product description.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Validate price
    if (empty(trim($_POST["price"]))) {
        $price_err = "Please enter the product price.";
    } else {
        $price = trim($_POST["price"]);
    }

    // Validate stock quantity
    if (empty(trim($_POST["stock_quantity"]))) {
        $stock_quantity_err = "Please enter the stock quantity.";
    } else {
        $stock_quantity = trim($_POST["stock_quantity"]);
    }

    // Validate category
    if (empty(trim($_POST["category"]))) {
        $category_err = "Please select a product category.";
    } else {
        $category = trim($_POST["category"]);
    }

    // If no errors, insert product into the database
    if (empty($name_err) && empty($description_err) && empty($price_err) && empty($stock_quantity_err) && empty($category_err)) {
        // Handle image upload
        if ($_FILES['image']['name']) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $upload_ok = 1;

            // Check if image file is an actual image
            if (getimagesize($_FILES['image']['tmp_name']) === false) {
                $upload_ok = 0;
                echo "File is not an image.";
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $upload_ok = 0;
                echo "Sorry, file already exists.";
            }

            // Check file size (limit to 5MB)
            if ($_FILES['image']['size'] > 5000000) {
                $upload_ok = 0;
                echo "Sorry, your file is too large.";
            }

            // Allow certain file formats (e.g., JPG, PNG, GIF)
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $upload_ok = 0;
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }

            // If everything is ok, upload the file
            if ($upload_ok == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    echo "The file " . htmlspecialchars(basename($_FILES['image']['name'])) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Insert product into database
        $sql = "INSERT INTO products (name, description, price, stock_quantity, category_id, image) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssdiss", $name, $description, $price, $stock_quantity, $category, $target_file);

            if ($stmt->execute()) {
                echo "<script>alert('Product added successfully!');</script>";
                // Redirect or handle accordingly
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>



<main>
    <section class="admin-dashboard">
        <h2>Add New Product</h2>
        <form action="product_management.php" method="POST" enctype="multipart/form-data">
            <!-- Product Name -->
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                <span class="error"><?php echo $name_err; ?></span>
            </div>

            <!-- Product Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?php echo $description; ?></textarea>
                <span class="error"><?php echo $description_err; ?></span>
            </div>

            <!-- Product Price -->
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" value="<?php echo $price; ?>" required>
                <span class="error"><?php echo $price_err; ?></span>
            </div>

            <!-- Stock Quantity -->
            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="number" id="stock_quantity" name="stock_quantity" value="<?php echo $stock_quantity; ?>" required>
                <span class="error"><?php echo $stock_quantity_err; ?></span>
            </div>

            <!-- Category Selection -->
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <?php
                    // Fetch categories from the database
                    $sql = "SELECT * FROM categories";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <span class="error"><?php echo $category_err; ?></span>
            </div>

            <!-- Product Image Upload -->
            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="admin-btn">Add Product</button>
        </form>
    </section>
</main>

<footer class="footer-container">
        <p>&copy; 2023 Organic Food Store. All rights reserved.</p>
    </footer>

</body>
</html>
