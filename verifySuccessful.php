<?php
session_start();
require 'vendor/autoload.php';
include "./templates/navigation.php";
include "./templates/functions.php";
include "./templates/errorReport.php";
include "./connection/con.php";// Your DB connection

//check email is already verify or not?
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT verified FROM Users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if already logged in
if (isset($_SESSION['user_id']) and $user["verified"] === 1) {
    header("Location: dashboard.php");
    exit;
}

// Get token from URL
$token = $_GET['token'] ?? null;
echo $token;

if (!$token) {
    echo "<p>Invalid verification link.</p>";
    exit;
}

// Find user with matching token
$stmt = $conn->prepare("SELECT id, verified FROM Users WHERE verify_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($user['verified'] == 1) {
        echo "<h3>Your email is already verified!</h3>";
    } else {
        // Update user as verified
        $update = $conn->prepare("UPDATE Users SET verified = 1, verify_token = NULL WHERE id = ?");
        $update->bind_param("i", $user['id']);
        $update->execute();

        echo "<h3>Email verified successfully! You can now log in.</h3>";
        header("Location: login.php");
        exit;
    }
} else {
    echo "<h3>Invalid or expired verification token.</h3>";
}

$stmt->close();
$conn->close();

include "./templates/footer.php";
?>
