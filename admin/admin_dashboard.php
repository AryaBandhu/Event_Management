<?php

session_start();


if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.html");
    exit();
}


include '../db_connection.php';
$conn = openConnection(); 


$vendorCount = $conn->query("SELECT COUNT(*) AS count FROM vendors")->fetch_assoc()['count'];
$userCount = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/admin_dashboard.css">
    <style>
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background-color: darkseagreen;
            text-align-last: center; 
        }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .main { margin-top: 20px; }
        .button-group { margin-top: 20px; }
        .button-group button {
            margin: 5px;
            padding: 10px 20px;
            border: none;
            border-radius: 30px;
        }


    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Admin Dashboard</h1>
            <form action="logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        </div>

        <div class="main">
            <div class="button-group">
                <button onclick="location.href='manage_vendors.php'">Manage Vendors (<?php echo $vendorCount; ?>)</button>
                <button onclick="location.href='manage_users.php'">Manage Users (<?php echo $userCount; ?>)</button>
            </div>
        </div>
    </div>
</body>
</html>
