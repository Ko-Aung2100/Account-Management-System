<?php 
include "./templates/navigation.php"; 

session_start(); // Start session

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}


?>

<div class="container mt-5" style="max-width: 600px; margin: auto;margin-bottom:150px;">    <h2>Register Form</h2>
    <form action="./validate.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" aria-describedby="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>        
        </div>
        <div class="mb-3">
            <label for="password"  class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    </div>
<?php include "./templates/footer.php"; ?>
