<?php

session_start();
session_unset(); 
session_destroy();

header("Location: vendor_login.html"); 
exit();
?>
