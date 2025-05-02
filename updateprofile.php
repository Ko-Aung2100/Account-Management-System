<?php
session_start();
include "./templates/errorReport.php";
include "./connection/con.php";
include "./templates/functions.php";
include "./templates/authCheck.php";


$user_id = $_SESSION['user_id'];
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

// Validate inputs
if (empty($username) || empty($email)) {
    die("Both username and email are required.");
}

$data = [$username, $email];
updateUser($conn, $user_id, $data);
$conn->close();
?>
