<?php
require 'conn.php';
session_start();

  if (isset($_SESSION["login"])) {
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
            $_SESSION["login"] = true;
            header('location:dashboard.php');
          }else{
            echo "Username atau Password salah";
          }
      }else{
          echo "Username atau Password salah";
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

  <div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
      <h3 class="text-center mb-4">Masuk <br> Dashboard Admin</h3>
      <form action="" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Username</label>
          <input type="text" class="form-control" name="username" placeholder="Enter username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        <p class="mt-3 text-center">
          <a href="#">Lupa password?</a>
        </p>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
