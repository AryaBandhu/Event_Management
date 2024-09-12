<?php
// manage_users.php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.html");
    exit();
}

$conn = openConnection();

// Fetch user details
$users = $conn->query("SELECT * FROM users");

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: manage_users.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="./css/manage_users.css">
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo isset($row['id']) ? htmlspecialchars($row['id']) : ''; ?></td>
                        <td><?php echo isset($row['name']) ? htmlspecialchars($row['name']) : ''; ?></td>
                        <td><?php echo isset($row['email']) ? htmlspecialchars($row['email']) : ''; ?></td>
                        <td><a href="manage_users.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
