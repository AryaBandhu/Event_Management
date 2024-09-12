<?php

session_start();
include '../db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $conn = openConnection(); 


    $sql = "SELECT * FROM vendors WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();


        if (password_verify($password, $row['password'])) {
            $_SESSION['vendor_username'] = $username; 
            header("Location: vendor_dashboard.php"); 
            exit();
        } else {

            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'vendor_login.html';
                    }, 2000); // 2 seconds delay
                  </script>";
            echo "Incorrect password. Redirecting to login page...";
        }
    } else {
        
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'vendor_login.html';
                }, 2000); // 2 seconds delay
              </script>";
        echo "Username not found. Redirecting to login page...";
    }


    $stmt->close();
    $conn->close();
}
?>
