<?php
session_start();

if (!isset($_SESSION['vendor_username'])) {
    header("Location: vendor_login.html"); 
    exit();
}

include '../db_connection.php';
$conn = openConnection();

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $productId = $_POST['product_id'];

    // Fetch the product image name from the database
    $stmt = $conn->prepare("SELECT product_image FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $productImage = $product['product_image'];
    
    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    if ($stmt->execute()) {
        // Remove the product image file from the server
        if ($productImage && file_exists('./uploads/' . $productImage)) {
            unlink('./uploads/' . $productImage);
        }
        echo "<p>Product deleted successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Fetch products added by the vendor
$vendorUsername = $_SESSION['vendor_username'];
$stmt = $conn->prepare("SELECT id, product_name, product_price, product_image FROM products WHERE vendor_username = ?");
$stmt->bind_param("s", $vendorUsername);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="./css/view_products.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background-color: #007bff; color: white; padding: 10px 20px; }
        .navbar h1 { margin: 0; }
        .navbar button { padding: 10px 20px; background-color: #ff4d4d; border: none; color: white; cursor: pointer; }
        .navbar button:hover { background-color: #cc0000; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        .delete-btn { background-color: #ff4d4d; color: white; border: none; padding: 5px 10px; cursor: pointer; text-decoration: none; }
        .delete-btn:hover { background-color: #cc0000; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Welcome Vendor, <?php echo htmlspecialchars($_SESSION['vendor_username']); ?></h1>
        <div>
            <a href="vendor_dashboard.php" style="color: white; margin-right: 20px;">Dashboard</a>
            <a href="add_product.php" style="color: white; margin-right: 20px;">Add Product</a>
            <form action="logout.php" method="post" style="display: inline;">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
    <div class="container">
        <h1>View All Products</h1>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Product Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_price']); ?></td>
                    <td><img src="./uploads/<?php echo htmlspecialchars($row['product_image']); ?>" alt="Product Image" width="100"></td>
                    <td>
                        <form action="view_products.php" method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
