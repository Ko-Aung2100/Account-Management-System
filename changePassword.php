<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start session

include "./templates/navigation.php";
include "./connection/con.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


if (!isset($_SESSION['password_confirmed']) || $_SESSION['password_confirmed'] !== true) {
  // Redirect back to password confirmation
  header("Location: passwordReset.php");
  exit();
}


// Initialize error message
$error_message = "";
$id = $_SESSION['user_id'];
// PHP validation on form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $password = $_POST["password"] ?? "";
    $password1 = $_POST["password1"] ?? "";

    // Server-side validation
    if (empty($password) || empty($password1)) {
        $error_message = "Both password fields are required.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } elseif ($password !== $password1) {
        $error_message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement to update the password
        $stmt = $conn->prepare("UPDATE Users SET password = ? WHERE id = ?");
        if ($stmt === false) {
            $error_message = "Failed to prepare the statement: " . $conn->error;
        } else {
            // Bind the hashed password and user_id
            $stmt->bind_param("si", $hashed_password, $id);

            // Execute the statement
            if ($stmt->execute()) {
                $success_message = "Password updated successfully!";
                // Optionally, unset the session variable to prevent reuse
                unset($_SESSION['password_confirmed']);
                header("Location: dashboard.php");
                exit;
                
            } else {
                $error_message = "Error updating password: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        }
    }
}

?>

<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow rounded-4">
          <div class="card-body p-4">
            <h3 class="mb-4 text-center">Change Password</h3>
            <?php if (!empty($error_message)): ?>
              <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
              <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
              </div>
            <?php endif; ?>
            <form action="changePassword.php" method="POST" novalidate onsubmit="return validateForm()">
              <div class="mb-3">
                <p>Please Enter Your New Password</p>
                <input type="password" class="form-control" id="password" name="password" required>
                <div id="passwordError" class="text-danger mt-1" style="font-size: 0.875em;"></div>
              </div>
              <div class="mb-3">
                <p>Please Enter Your New Password Again</p>
                <input type="password" class="form-control" id="password1" name="password1" required>
                <div id="password1Error" class="text-danger mt-1" style="font-size: 0.875em;"></div>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Enter</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

<script>
function validateForm() {
    // Get the password fields
    const password = document.getElementById("password").value;
    const password1 = document.getElementById("password1").value;

    // Get the error message elements
    const passwordError = document.getElementById("passwordError");
    const password1Error = document.getElementById("password1Error");

    // Reset error messages
    passwordError.textContent = "";
    password1Error.textContent = "";

    let isValid = true;

    // Check if passwords are empty
    if (!password) {
        passwordError.textContent = "Password is required.";
        isValid = false;
    }
    if (!password1) {
        password1Error.textContent = "Please confirm your password.";
        isValid = false;
    }

    // Check password length
    if (password.length < 8) {
        passwordError.textContent = "Password must be at least 8 characters long.";
        isValid = false;
    }

    // Check if passwords match
    if (password !== password1) {
        password1Error.textContent = "Passwords do not match.";
        isValid = false;
    }

    return isValid;
}
</script>

<?php include "./templates/footer.php"; ?>