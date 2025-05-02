<?php 
include "templates/navigation.php"; 
session_start(); // Start session
if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit;
}

?>
<h1 class="mt-3 mx-5">Demo Account Management System for Midterm Essay</h1>
<h2 class="mt-3 mx-5">Developer Team</h2>
<div class="container my-5">
  <div class="row">
    <div class="col-md-4">
      <div class="card shadow">
        <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Aung Kaung Htet</h5>
          <p class="card-text">This is the first card.</p>
          <a href="#" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow">
        <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Wai Yan Moe Myint</h5>
          <p class="card-text">This is the second card.</p>
          <a href="#" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow">
        <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Nan Hnin Yay Kyi</h5>
          <p class="card-text">This is the third card.</p>
          <a href="#" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include "templates/footer.php"; ?>
