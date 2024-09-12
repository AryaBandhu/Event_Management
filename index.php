<?php
session_start();
include 'db_connection.php';
$conn = openConnection();

// Fetch products from the database
$query = "SELECT * FROM products";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching products: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
        }
        .navbar h1 {
            margin: 0;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
        }
        .navbar a:hover {
            background-color: #0056b3;
            border-radius: 5px;
        }
        .container {
            text-align: center;
            padding: 50px;
        }
        .container h1 {
            font-size: 3em;
            margin-bottom: 20px;
            color: #007bff;
        }
        .buttons {
            margin: 20px 0;
        }
        .buttons button {
            padding: 15px 30px;
            font-size: 1.2em;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            background-color: #007bff;
        }
        .buttons button:hover {
            background-color: #0056b3;
        }
        .products {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            text-align: center;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .product-card h3 {
            margin: 10px 0;
        }
        .product-card p {
            margin: 5px 0;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Event Management System</h1>
        <div>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </div>
    </div>
    <div class="container">
        <h1>Welcome to the Event Management System</h1>
        <div class="buttons">
            <button onclick="window.location.href='./admin/login/admin_login.html'">Admin Login</button>
            <button onclick="window.location.href='vendor/vendor_login.html'">Vendor Login</button>
            <button onclick="window.location.href='./user/user_login.html'">User Login</button>
        </div>
        <h2>Featured Products</h2>
        <div class="products">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="vendor/uploads/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                    <p>$<?php echo number_format($row['product_price'], 2); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
