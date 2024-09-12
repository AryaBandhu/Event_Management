<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: user_login.html"); 
    exit();
}

$conn = openConnection();

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    unset($_SESSION['cart'][$productId]);
}

// Handle placing order
if (isset($_POST['place_order'])) {
    // Process the order here
    // Clear the cart after placing order
    unset($_SESSION['cart']);
    echo "<p>Order placed successfully!</p>";
}

// Fetch products for cart display
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();

if (!empty($cartItems)) {
    $productIds = implode(',', array_keys($cartItems));
    $query = "SELECT * FROM products WHERE product_id IN ($productIds)";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $products[$row['product_id']] = $row;
    }
}

$totalPrice = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="./css/user_dashboard.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background-color: #007bff; color: white; padding: 10px 20px; }
        .navbar h1 { margin: 0; }
        .navbar form { margin: 0; }
        .navbar button { padding: 10px 20px; background-color: #ff4d4d; border: none; color: white; cursor: pointer; }
        .navbar button:hover { background-color: #cc0000; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .product { display: flex; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .product img { width: 100px; height: 100px; object-fit: cover; margin-right: 20px; }
        .product-details { flex-grow: 1; }
        .product-actions { display: flex; align-items: center; }
        .product-actions select { margin-right: 10px; }
        .product-actions button { padding: 5px 10px; background-color: #ff4d4d; border: none; color: white; cursor: pointer; }
        .product-actions button:hover { background-color: #cc0000; }
        .total-price { font-weight: bold; margin-top: 20px; }
        .final-price { font-weight: bold; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
        button.place-order { padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button.place-order:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Hello, <?php echo htmlspecialchars($_SESSION['user_email']); ?></h1>
        <div>
            <form action="user_dashboard.php" method="get" style="display:inline;">
                <button type="submit">View Products</button>
            </form>
            <form action="logout.php" method="post" style="display:inline;">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
    <div class="container">
        <h1>Shopping Cart</h1>
        <?php if (!empty($cartItems)) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="product">
                    <img src="./uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <div class="product-details">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p>Price: $<?php echo number_format($product['product_price'], 2); ?></p>
                    </div>
                    <div class="product-actions">
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                            <select name="quantity">
                                <?php for ($i = 1; $i <= 10; $i++) : ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($i == $cartItems[$product['product_id']]) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" name="add_to_cart">Update Quantity</button>
                        </form>
                        <form action="add_to_cart.php" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                            <button type="submit" name="remove_from_cart">Remove</button>
                        </form>
                        <span>$<?php echo number_format($cartItems[$product['product_id']] * $product['product_price'], 2); ?></span>
                    </div>
                </div>
                <?php $totalPrice += $cartItems[$product['product_id']] * $product['product_price']; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
        <div class="final-price">
            <p>Total Price: $<?php echo number_format($totalPrice, 2); ?></p>
            <?php if (!empty($cartItems)) : ?>
                <form action="add_to_cart.php" method="post">
                    <button type="submit" name="place_order" class="place-order">Place Order</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
