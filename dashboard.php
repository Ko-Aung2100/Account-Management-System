<?php 

session_start(); // Start session

include "./templates/navigation.php"; 
include "./connection/con.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM Users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
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
              <h5 class="card-title"><?php echo $user['username'] ?></h5>
              <p class="card-text"><strong>Email:</strong> <?php echo $user['email'] ?></p>
              <a href="editProfile.php" class="btn btn-primary btn-sm">Edit Profile</a>
              <br>
              <br>
              <a href="passwordReset.php"><button class="btn btn-primary">Reset Password?</button></a>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>

<?php include "./templates/footer.php"; ?>
