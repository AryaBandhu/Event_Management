<?php

include '../db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    if (!empty($name) && !empty($email) && !empty($password)) {
        $conn = openConnection(); 


        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "Signup successful! You can now <a href='user_login.html'>login</a>.";
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
