<?php
include "./templates/navigation.php";
include "./connection/con.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start session

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    if (!empty($email) && !empty($password)) {
        // Prepare statement to avoid SQL injection
        $stmt = $conn->prepare("SELECT id, email, password FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                // Login success
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_email"] = $user["email"];
                header("Location: dashboard.php"); // Redirect to a protected page
                exit;
            } else {
                $loginError = "Incorrect password.";
            }
        } else {
            $loginError = "No account found with that email.";
        }
        $stmt->close();
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
      <?php echo $loginError ?>
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

