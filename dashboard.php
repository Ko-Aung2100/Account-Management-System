<?php 
session_start(); // Start session
include "./templates/navigation.php"; 
include "./templates/functions.php";
include "./connection/con.php";
include "./templates/errorReport.php";
include "./templates/authCheck.php";

$user_id = $_SESSION['user_id'];
$user = showRecords($conn,$user_id);
$conn->close();
?>

<!-- Main Content -->
<div class="main-content mt-5 mx-auto">
  <div class="container">
    <h2>User Profile</h2>
    <div class="card mt-4 shadow-sm" style="max-width: 540px;">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="./images/avatar.png" class="img-fluid rounded-start" alt="User Avatar">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title"><?php echo escape($user['username']); ?></h5>
            <p class="card-text"><strong>Email:</strong> <?php echo escape($user['email']); ?></p>
            <a href="editProfile.php" class="btn btn-primary btn-sm">Edit Profile</a>
            <br><br>
            <a href="passwordReset.php"><button class="btn btn-primary">Reset Password?</button></a>
            <br><br>
            <a href="2fa_setup.php"><button class="btn btn-primary">Setup Two-Factor Authentication</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "./templates/footer.php"; ?>
