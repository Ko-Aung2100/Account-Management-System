<?php
include "./templates/errorReport.php";
include "./templates/functions.php";
include "./connection/con.php";
session_start();

// Check if already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['insession'])) {
    header("Location: dashboard.php");
    exit;
}

// Check if request method is not POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirect to previous page or fallback
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'register.php'; // Fallback to index.php
    header("Location: $redirectUrl");
    exit;
}

$token = bin2hex(random_bytes(16)); 
$username = htmlspecialchars($_POST["username"]);
$email = htmlspecialchars($_POST["email"]);
$password = $_POST["password"];

$_SESSION['email'] = $email;
$_SESSION['username'] = $username;
$_SESSION['token'] = $token;

// --- Validation ---
validateUserInput($username, $email, $password, $conn) ;
// --- Secure Password Hashing ---
$success =createUser($conn, $username, $email, $password,$token);
var_dump($success);
// Execute the statement
if ($success) {
    echo "Registration successful!";
    $_SESSION['registered'] = true;
    header("Location: verify.php");
    exit();
} else {
    error_log("Error executing statement: " . $stmt->error);
    echo "An error occurred during registration. Please try again later.";
}
mysqli_close($conn); // Close the connection when done
?>