<?php
require 'conn.php';
session_start();

  if (isset($_SESSION["user"])) {
    header('location:dashboard.php');
    exit;
}
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $username = $_POST['username'];
      $password = $_POST['password'];

      $cekdb = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
      if( mysqli_num_rows($cekdb) > 0 ){
          $row = mysqli_fetch_assoc($cekdb);
    
          if( $password == $row["password"] ){
            $_SESSION["user"] = $username;
            header('location:dashboard.php');
          }else{
            $_SESSION['error'] = "Username atau Password salah";
          }
      }else{
          $_SESSION['error'] = "Username atau Password salah";
      }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
  body {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../assets/welcome.jpg') no-repeat center center fixed;
    background-size: cover;
  }
</style>

<body class="bg-light">

  <!-- Error Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="errorModalLabel">Login Gagal</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php
          if (isset($_SESSION['error'])) {
              echo $_SESSION['error'];
              unset($_SESSION['error']);
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card p-3 shadow" style="max-width: 400px; width: 100%;">
      <h3 class="text-center mb-2">Masuk <br> Dashboard Admin</h3>
      <form action="" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Username</label>
          <input type="text" class="form-control" name="username" placeholder="Enter username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"
            required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var errorModal = document.getElementById('errorModal');
      var modalBody = errorModal.querySelector('.modal-body').innerText.trim();

      if (modalBody !== "") {
        var modal = new bootstrap.Modal(errorModal);
        modal.show();
      }
    });
  </script>
</body>

</html>