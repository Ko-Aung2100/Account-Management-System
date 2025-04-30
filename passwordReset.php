<?php 
session_start(); // Start session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "./templates/navigation.php";
include "./connection/con.php";



// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];

    if (!empty($password)) {
            // Prepare statement to avoid SQL injection
            $stmt = $conn->prepare("SELECT password FROM Users WHERE id = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user["password"])) {
                    header("Location: changePassword.php"); // Redirect to a protected page
                    exit;
                } else {
                echo "Incorrect password";
                }
            }
            $stmt->close();
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow rounded-4">
          <div class="card-body p-4">
            <h3 class="mb-4 text-center">Please Enter Your password to Continue.</h3>
            <form action="passwordReset.php" method="POST" novalidate>
              
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Enter</button>
              </div>
            </form>
            <div class="text-center mt-3">
              <small>Forget your password? <a href="checkmail.php">Reset Here</a></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include "./templates/footer.php";?>