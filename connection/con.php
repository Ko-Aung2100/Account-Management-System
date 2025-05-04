<?php
$servername = "localhost"; // or your server's address
$username = "phpmyadmin";
$password = "root";
$dbname = "File_Management";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//echo "Connected successfully";

// ... your database operations ...

?>