<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start session

include "./connection/con.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Retrieve the logged-in user's ID from the session
$username = $_POST['username'];
$email = $_POST['email'];

// Update the user table with the new username and email
$sql = "UPDATE Users SET username = ?, email = ? WHERE id = ?";

// Prepare the query
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
// Bind parameters
$stmt->bind_param("ssi", $username, $email, $user_id);

// Execute the query
if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Error updating profile: " . $stmt->error;
}

// Close the prepared statement and connection
$stmt->close();
$conn->close();
?>