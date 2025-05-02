<?php
session_start();
require_once 'vendor/autoload.php';
include "./templates/navigation.php"; 
include "./templates/functions.php";
include "./connection/con.php";
use OTPHP\TOTP;

$userId = $_SESSION['user_id'] ?? 1;
$code = $_POST['code'] ?? '';

//you cannot come back here after login to dashboard
if(isset($_SESSION['user_id']) && isset($_SESSION["insession"]) && $_SESSION["insession"] === true){
  header("Location: dashboard.php");
  exit;
}
if(!isset($_SESSION["2fa"]) && $_SESSION["2fa"]!== true){
  header("Location: login.php");
  exit;
}
// Get user's secret
$arr=["secret"];
$row = showRecordsSelective($conn, $arr, $userId);

$totp = TOTP::create($row['secret']);

  if ($totp->verify($code)) {
      $_SESSION["insession"] = true;
      //mark user as log in
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
                <input type="hidden" name="otp" value="self">
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