<?php

include '../db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $category = $_POST['category'];


    if (!empty($username) && !empty($email) && !empty($password) && !empty($category)) {

        $conn = openConnection();


        $sql = "SELECT * FROM vendors WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

           
            $sql = "INSERT INTO vendors (username, email, password, category) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $category);

            if ($stmt->execute()) {
                echo "Signup successful! You can now <a href='vendor_login.html'>login</a>.";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Email already exists. Please use a different email.";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "All fields are required.";
    }
}
?>
