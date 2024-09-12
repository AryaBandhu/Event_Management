<?php

session_start();
include '../db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $conn = openConnection(); 


    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();


        if (password_verify($password, $row['password'])) {
            $_SESSION['user_email'] = $username;
            header("Location: user_dashboard.php"); 
            exit();
        } else {

            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'user_login.html';
                    }, 2000); // 2 seconds delay
                  </script>";
            echo "Incorrect password. Redirecting to login page...";
        }
    } else {

        echo "<script>
                setTimeout(function() {
                    window.location.href = 'user_login.html';
                }, 2000); // 2 seconds delay
              </script>";
        echo "User not found. Redirecting to login page...";
    }


    $stmt->close();
    $conn->close();
}
?>
