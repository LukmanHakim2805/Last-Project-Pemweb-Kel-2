<?php
require 'conn.php';
session_start();

if (!isset($_SESSION["user"])) {
    header('location:index.php');
    exit;
}

// Hitung data laporan
$q_penjualan = mysqli_query($conn, "SELECT SUM(total) AS total_penjualan FROM transaksi");
$total_penjualan = mysqli_fetch_assoc($q_penjualan)['total_penjualan'] ?? 0;

$q_hpp = mysqli_query($conn, "
  SELECT SUM(dt.jumlah * p.harga_modal) AS hpp
  FROM detail_transaksi dt
  JOIN produk p ON dt.id_produk = p.id
");
$total_hpp = mysqli_fetch_assoc($q_hpp)['hpp'] ?? 0;

$total_kotor = $total_penjualan - $total_hpp;

// Pendapatan lain dan pengeluaran tidak tersedia di database
$pendapatan_lain = 0;
$pengeluaran = 0;
$laba_bersih = $total_kotor + $pendapatan_lain - $pengeluaran;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Penjualan - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
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
    .content {
      margin-left: 17%;
      padding: 40px;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-white mb-4">Dashboard Dasha</h4>
        <nav class="nav flex-column">
          <a href="dashboard.php" class="nav-link">Dashboard</a>
          <a href="transaksi.php" class="nav-link">Transaksi</a>
          <a href="stok.php" class="nav-link">Manajemen Stok</a>
          <a href="laporan.php" class="nav-link active">Laporan Penjualan</a>
          <a href="logout.php" class="nav-link text-danger mt-auto">Keluar</a>
        </nav>
      </div>

      <!-- Main content -->
      <div class="col-md-10 offset-md-2 content">
        <h3 class="mb-4">Laporan Penjualan</h3>

        <table class="table table-bordered w-75">
          <tbody>
            <tr>
              <th>Penjualan</th>
              <td class="text-end">Rp <?= number_format($total_penjualan, 0, ',', '.') ?></td>
            </tr>
            <tr>
              <th>Harga Pokok Penjualan</th>
              <td class="text-end text-danger">(Rp <?= number_format($total_hpp, 0, ',', '.') ?>)</td>
            </tr>
            <tr>
              <th>Total Penjualan Kotor</th>
              <td class="text-end fw-bold">Rp <?= number_format($total_kotor, 0, ',', '.') ?></td>
            </tr>
            <tr>
              <th colspan="2" class="bg-light">Pendapatan Lain</th>
            </tr>
            <tr>
              <th>Total Pendapatan Lain</th>
              <td class="text-end">Rp <?= number_format($pendapatan_lain, 0, ',', '.') ?></td>
            </tr>
            <tr>
              <th colspan="2" class="bg-light">Pengeluaran</th>
            </tr>
            <tr>
              <th>Total Pengeluaran</th>
              <td class="text-end">Rp <?= number_format($pengeluaran, 0, ',', '.') ?></td>
            </tr>
            <tr>
              <th class="bg-success-subtle">Laba Bersih</th>
              <td class="text-end fw-bold text-success">Rp <?= number_format($laba_bersih, 0, ',', '.') ?></td>
            </tr>
          </tbody>
        </table>

        <div class="mt-4">
          <a href="export_excel.php" class="btn btn-success">Export ke Excel</a>
          <a href="export_pdf.php" class="btn btn-danger">Export ke PDF</a>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
