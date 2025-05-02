<?php
include "./templates/errorReport.php";
session_start();

// Check if already logged in
if (isset($_SESSION['user_id'])) {
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

include "./connection/con.php";

$token = bin2hex(random_bytes(16)); 

$username = htmlspecialchars($_POST["username"]);
$email = htmlspecialchars($_POST["email"]);
$password = $_POST["password"];

$_SESSION['email'] = $email;
$_SESSION['username'] = $username;
$_SESSION['token'] = $token;
//echo $username . " " . $email . " " . $password;
// --- Validation ---
$errors = [];

if (empty($username)) {
    $errors[] = 'Username is required.';
} elseif (strlen($username) > 50) {
    $errors[] = 'Username cannot exceed 50 characters.';
}

if (empty($email)) {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
} elseif (strlen($email) > 100) {
    $errors[] = 'Email cannot exceed 100 characters.';
}

if (empty($password)) {
    $errors[] = 'Password is required.';
} elseif (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters long.';
}

if (!empty($errors)) {
    // Handle validation errors (e.g., display them to the user)
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
    $conn->close(); // Close the connection on error
    exit(); // Stop further processing
}

// --- Check for Duplicate Email ---
$sql = "SELECT * FROM Users WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Handle preparation errors
    error_log("Error preparing statement: " . $conn->error);
    echo "An error occurred during registration. Please try again later.";
    $conn->close();
    exit();
}

// Bind the email parameter to the query
$stmt->bind_param("s", $email);

// Execute the statement
$stmt->execute();

// Check if the email already exists
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Email is already in use
    $errors[] = 'This email is already registered. Please use a different email address.';
}

// If there are any validation errors, display them
if (!empty($errors)) {
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    $stmt->close();
    $conn->close();
    exit(); // Stop further processing if errors exist
}

// --- Secure Password Hashing ---
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// --- Prepare and Execute a Prepared Statement (mysqli) ---
$sql = "INSERT INTO Users (username, email, password, verify_token) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Handle preparation errors (log them, display a user-friendly message)
    error_log("Error preparing statement: " . $conn->error);
    echo "An error occurred during registration. Please try again later.";
    $conn->close();
    exit();
}

// Bind parameters
$stmt->bind_param("ssss", $username, $email, $hashedPassword, $token);
// "sss" indicates the types of the bound parameters (string, string, string)

// Execute the statement
if ($stmt->execute()) {
    echo "Registration successful!";
    // Optionally redirect the user
    header("Location: verify.php");
    exit();
} else {
    // Handle execution errors (log them, display a user-friendly message)
    error_log("Error executing statement: " . $stmt->error);
    echo "An error occurred during registration. Please try again later.";
}

// Close the statement and the connection
$stmt->close();
mysqli_close($conn); // Close the connection when done
?>