<?php
require 'conn.php';
session_start();

if (!isset($_SESSION["user"])) {
    header('location:index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Laporan Penjualan - Toko Dasha</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f1f3f5;
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      position: fixed;
      height: 100%;
      background-color: rgb(34, 53, 71);
      padding: 25px 15px;
    }
    .sidebar .nav-link {
      color: #adb5bd;
      border-radius: 5px;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background-color: rgb(95, 168, 241);
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-white mb-4">Toko Dasha</h4>
        <nav class="nav flex-column">
          <a href="dashboard.php" class="nav-link">Dashboard</a>
          <a href="#" class="nav-link active">Transaksi</a>
          <a href="stok.php" class="nav-link">Manajemen Stok</a>
          <a href="laporan.php" class="nav-link">Laporan Penjualan</a>
          <a href="logout.php" class="nav-link text-danger mt-auto">Keluar</a>
        </nav>
      </div>

      <!-- konten utama -->
      <div class="col-md-10 offset-md-2 p-4">

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
