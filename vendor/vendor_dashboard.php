<?php
session_start();

if (!isset($_SESSION['vendor_username'])) {
    header("Location: vendor_login.html"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="./css/vendor_dashboard.css">
    <style>
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .button-group { margin-top: 20px; }
        .button-group a { display: inline-block; padding: 10px 20px; margin: 10px; text-decoration: none; color: white; background-color: #007bff; border-radius: 5px; }
        .button-group a:hover { background-color: #0056b3; }
        form button { padding: 10px 20px; margin: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Your Dashboard</h1>
            <form action="logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        </div>
        <div class="main">
            <p>Hello, <?php echo htmlspecialchars($_SESSION['vendor_username']); ?>!</p>
            <div class="button-group">
                <a href="add_product.php">Add Product</a>
                <a href="view_products.php">View My Products</a>
                <a href="view_transactions.php">View Transactions</a>
            </div>
        </div>
    </div>
</body>
</html>
