<?php 
session_start(); // Start session
include "./templates/navigation.php"; 
include "./templates/functions.php";
include "./connection/con.php";
include "./templates/authCheck.php";

$user_id = $_SESSION['user_id'];
$user = showRecords($conn,$user_id);
$conn->close();
?>
<div class="container">
    <h2>Edit Profile</h2>
    <form action="updateprofile.php" method="POST" class="mt-4 needs-validation" novalidate>
      <div class="mb-3">
        <label for="fullName" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo escape($user['username']) ?>" required>
        <div class="invalid-feedback">Please enter your new username.</div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo escape($user['email']) ?>" required>
        <div class="invalid-feedback">Please enter a valid email.</div>
      </div>

      <button type="submit" class="btn btn-primary">Update Profile</button>
      <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Optional JS for validation -->
  <script>
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
<?php include "./templates/footer.php"; ?>