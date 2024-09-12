<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: user_login.html"); 
    exit();
}

include '../db_connection.php';
$conn = openConnection();

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
    <title>User Dashboard</title>
    <link rel="stylesheet" href="./css/user_dashboard.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
        }
        .navbar h1 { margin: 0; }
        .navbar button {
            padding: 10px 20px;
            background-color: #ff4d4d;
            border: none;
            color: white;
            cursor: pointer;
        }
        .navbar button:hover { background-color: #cc0000; }
        .container { padding: 20px; }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .product-card h3 { margin: 10px 0 5px; }
        .product-card p { margin: 0 0 10px; }
        .product-card button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .product-card button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Hello, <?php echo htmlspecialchars($_SESSION['user_email']); ?></h1>
        <div>
            <a href="add_to_cart.php"><button>Cart</button></a>
            <form action="logout.php" method="post" style="display:inline;">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
    <div class="container">
        <h2>Available Products</h2>
        <div class="product-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="../vendor/uploads/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                    <p>Price: $<?php echo htmlspecialchars($row['product_price']); ?></p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
