<?php
session_start();

if (!isset($_SESSION['vendor_username'])) {
    header("Location: vendor_login.html"); 
    exit();
}

include '../db_connection.php';
$conn = openConnection(); 

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName = $_FILES['product_image']['name'];
        $fileSize = $_FILES['product_image']['size'];
        $fileType = $_FILES['product_image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExts = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $stmt = $conn->prepare("INSERT INTO products (vendor_username, product_name, product_price, product_image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $_SESSION['vendor_username'], $productName, $productPrice, $newFileName);
                
                if ($stmt->execute()) {
                    echo "<p>Product added successfully!</p>";
                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }
                
                $stmt->close();
            } else {
                echo "<p>There was an error uploading the file, please try again.</p>";
            }
        } else {
            echo "<p>Unsupported file type. Please upload only JPG, JPEG, PNG, or GIF files.</p>";
        }
    } else {
        echo "<p>Please select a file to upload.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="./css/add_product.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background-color: #007bff; color: white; padding: 10px 20px; }
        .navbar h1 { margin: 0; }
        .navbar button { padding: 10px 20px; background-color: #ff4d4d; border: none; color: white; cursor: pointer; }
        .navbar button:hover { background-color: #cc0000; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        form { display: flex; flex-direction: column; }
        label { margin: 10px 0 5px; }
        input[type="text"], input[type="number"], input[type="file"] { padding: 10px; margin-bottom: 15px; }
        button { padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Welcome Vendor, <?php echo htmlspecialchars($_SESSION['vendor_username']); ?></h1>
        <div>
            <a href="vendor_dashboard.php" style="color: white; margin-right: 20px;">Dashboard</a>
            <a href="view_products.php" style="color: white; margin-right: 20px;">View All Products</a>
            <form action="logout.php" method="post" style="display: inline;">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
    <div class="container">
        <h1>Add Product</h1>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required>

            <label for="product_price">Product Price:</label>
            <input type="number" id="product_price" name="product_price" step="0.01" required>

            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept=".jpg, .jpeg, .png, .gif" required>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
