<?php
session_start();
require 'vendor/autoload.php';
include "./templates/navigation.php";
include "./templates/functions.php";
include "./templates/errorReport.php";
include "./connection/con.php";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: /dashboard.php");
    exit;
}
if (isset($_SESSION["insession"])) {
    header("Location: dashboard.php");
    exit;
}

// Get token from URL
$token = $_GET['token'] ?? null;
$token = trim($token);

// Validate token presence
if (!$token) {
    showAlert("Invalid reset link.", "error");
    header("Location: /index.php");
    exit;
}

$update = false;

// Look up user by token
$stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Check token validity
if (!$user) {
    showAlert("Reset link is invalid or already used.", "error");
    header("Location: /index.php");
    exit;
}

// Check expiration
$current = new DateTime();
$expires = new DateTime($user['expires_at']);
if ($current > $expires) {
    showAlert("This reset link has expired.", "error");
    header("Location: /index.php");
    exit;
}

$update = true;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password1 = $_POST["password1"];

    // Server-side match check
    if ($password !== $password1) {
        showAlert("Passwords do not match.", "error");
    } elseif (strlen($password) < 8) {
        showAlert("Password must be at least 8 characters.", "error");
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update user password
        $updateStmt = $conn->prepare("UPDATE Users SET password = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        if ($updateStmt->execute() && $updateStmt->affected_rows > 0) {
            // Delete token
            $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE email = ? AND token = ?");
            $deleteStmt->bind_param("ss", $email, $token);
            $deleteStmt->execute();
            $deleteStmt->close();

            showAlert("Password updated successfully.", "success");
            header("Location: /login.php");
            exit;
        } else {
            showAlert("Failed to update password.", "error");
        }
        $updateStmt->close();
    }
}
?>

<?php if ($update): ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow rounded-4">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-center">Change Password</h3>
                    <form action="" method="POST" novalidate onsubmit="return validateForm()">
                        <div class="mb-3">
                            <p>New Password</p>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div id="passwordError" class="text-danger mt-1" style="font-size: 0.875em;"></div>
                        </div>
                        <div class="mb-3">
                            <p>Confirm New Password</p>
                            <input type="password" class="form-control" id="password1" name="password1" required>
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($user["email"]); ?>">
                            <div id="password1Error" class="text-danger mt-1" style="font-size: 0.875em;"></div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function validateForm() {
    const password = document.getElementById("password").value;
    const password1 = document.getElementById("password1").value;
    const passwordError = document.getElementById("passwordError");
    const password1Error = document.getElementById("password1Error");
    passwordError.textContent = "";
    password1Error.textContent = "";
    let isValid = true;

    if (!password) {
        passwordError.textContent = "Password is required.";
        isValid = false;
    }
    if (!password1) {
        password1Error.textContent = "Please confirm your password.";
        isValid = false;
    }
    if (password.length < 8) {
        passwordError.textContent = "Password must be at least 8 characters long.";
        isValid = false;
    }
    if (password !== password1) {
        password1Error.textContent = "Passwords do not match.";
        isValid = false;
    }

    return isValid;
}
</script>

<?php include "./templates/footer.php"; ?>
