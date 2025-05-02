<?php
session_start();
require_once 'vendor/autoload.php';
include "./templates/navigation.php"; 
include "./templates/functions.php";
include "./connection/con.php";
use OTPHP\TOTP;


$userId = $_SESSION['user_id'] ?? 1;
$code = $_POST['code'] ?? '';

// Get user's secret
$stmt = $conn->prepare("SELECT secret FROM Users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totp = TOTP::create($row['secret']);

if ($totp->verify($code)) {
    $_SESSION['authenticated'] = true;
    header('Location: dashboard.php');
    exit;
} else {
    echo "❌ Invalid code!";
}
?>


<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow rounded-4">
          <div class="card-body p-4">
            <h3 class="mb-4 text-center">Please Enter Your OTP Code.</h3>
            <form action="2fa_verify.php" method="POST" novalidate>
              <div class="mb-3">
                <label for="code" class="form-label">OTP</label>
                <br>
                <input type="text" class="form-control" id="code" name="code" required>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include "./templates/navigation.php"; ?>