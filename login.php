<?php
include "./templates/errorReport.php";
include "./templates/navigation.php";
include "./templates/functions.php";
session_start();

if (isset($_SESSION['insession'])) {
    header("Location: dashboard.php");
    exit;
}

$loginError = $_SESSION["error"] ?? "";
$ip = $_SERVER['REMOTE_ADDR'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    // Rate limiting: Check failed attempts in last 15 minutes
    $stmt = $conn->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE (email = ? OR ip_address = ?) AND attempt_time > (NOW() - INTERVAL 3 MINUTE)");
    $stmt->bind_param("ss", $email, $ip);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $attempts = $result['attempts'] ?? 0;
    $stmt->close();

    if ($attempts >= 3) {
        $loginError = "Too many failed attempts. Try again after 3 minutes.";
    } elseif (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, email, password, verified, secret FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($user["verified"] === 0) {
                $_SESSION['error'] = "Please verify your email first to login.";
                $stmt->close();
                header("Location: login.php");
                exit;
            }

            if (password_verify($password, $user["password"])) {
                // Login successful - clear previous attempts
                $clear = $conn->prepare("DELETE FROM login_attempts WHERE email = ? OR ip_address = ?");
                $clear->bind_param("ss", $email, $ip);
                $clear->execute();
                $clear->close();

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_email"] = $user["email"];

                if (!empty($user["secret"])) {
                    $_SESSION["2fa"] = true;
                    header("Location: 2fa_verify.php");
                } else {
                    $_SESSION["insession"] = true;
                    header("Location: dashboard.php");
                }
                exit;
            } else {
                $loginError = "Incorrect password.";
            }
        } else {
            $loginError = "No account found with that email.";
        }
        $stmt->close();

        // Log failed login
        if ($loginError !== "") {
            $log = $conn->prepare("INSERT INTO login_attempts (email, ip_address) VALUES (?, ?)");
            $log->bind_param("ss", $email, $ip);
            $log->execute();
            $log->close();
        }
    } else {
        $loginError = "Please fill in all fields.";
    }
}
?>


<div class="container mt-5">
    <div class="<?php 
      if($loginError == "") {
      echo "hidden" ; 
      }else{ 
        echo "show" ;
      };?>"
      >
      <?php showAlert($loginError, "error") ?>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow rounded-4">
          <div class="card-body p-4">
            <h3 class="mb-4 text-center">Login</h3>
            <form action="login.php" method="POST" novalidate>
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <input type="hidden" name="action" value="login">
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Log In</button>
              </div>
            </form>
            <div class="text-center mt-3">
              <small>Don't have an account? <a href="register.php">Register here</a></small>
            </div>
            <div class="text-center mt-3">
              <small>Forget your password? <a href="passwordResetExternal.php">Reset Here</a></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include "./templates/footer.php"; ?>

