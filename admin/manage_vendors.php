<?php

session_start();
include '../db_connection.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.html");
    exit();
}

$conn = openConnection();


$vendors = $conn->query("SELECT * FROM vendors");


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM vendors WHERE id = $id");
    header("Location: manage_vendors.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vendors</title>
    <link rel="stylesheet" href="./css/manage_vendors.css">
</head>
<body>
    <div class="container">
        <h1>Manage Vendors</h1>
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $vendors->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><a href="manage_vendors.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this vendor?')">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
