<?php
session_start();
require 'vendor/autoload.php';
include "./templates/navigation.php";
include "./templates/functions.php";
include "./templates/errorReport.php";
include "./connection/con.php"; // Your DB connection

if (isset($_SESSION['user_id'])) {
    header("Location: /dashboard.php");
    exit;
}

// Get token from URL path (or query param)
$token = $_GET['token'] ?? basename($_SERVER['REQUEST_URI']); // fallback for token in path

// Clean token
$token = trim($token);

// 1. Reject if no token
if (!$token || $token === 'verifySuccessful.php') {
    echo "<p>Invalid verification link.</p>";
    exit;
}

// 2. Look up user by token
$stmt = $conn->prepare("SELECT id, verified FROM Users WHERE verify_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

// 3. Token found?
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($user['verified'] == 1) {
        // Already verified → redirect
        header("Location: /login.php");
        exit;
    } else {
        // Verify user
        $update = $conn->prepare("UPDATE Users SET verified = 1, verify_token = NULL WHERE id = ?");
        $update->bind_param("i", $user['id']);
        $update->execute();

        echo "<h3>Email verified successfully! You can now log in.</h3>";
        header("Refresh: 2; URL=login.php");
        exit;
    }
} else {
    echo "<h3>Invalid or expired verification token.</h3>";
}

$stmt->close();
$conn->close();

include "./templates/footer.php";
?>
