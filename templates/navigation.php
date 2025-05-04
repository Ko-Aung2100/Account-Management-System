<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Managment System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .hidden{
      display:none;
    }
    .show{
      display:block;
    }
  </style>
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Account Management System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item active">
        <?php 
           if(!isset($_SESSION['user_id'])){
            ?>
             <a class="nav-link" href="/index.php">Home</a>
           <?php
           }
           ?>
           
        
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <?php 
           if(isset($_SESSION['user_id'])){
           ?>
            <a class="nav-link" href="logout.php">Logout</a>
          <?php
           } else{
              if ($currentPage !== 'login.php'){
          ?>
             <a class="nav-link" href="/login.php">Login</a>
          <?php
              }
            }
          ?>
        </li>
      </ul>
    </div>
  </div>
</nav>

<body class="d-flex flex-column min-vh-100">