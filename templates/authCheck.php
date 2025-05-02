<?php
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT secret FROM Users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle case when user is not found
if (!$user) {
    echo "User not found.";
    exit;
}
if(!is_null($user['secret'])){
    // If user has 2FA secret, but has not passed verification, redirect to 2FA verify
    if (!empty($user['secret']) && (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true)) {
        header("Location: 2fa_verify.php");
        exit;
    }
}
$stmt->close();
?>