<?php
require 'conn.php';
session_start();

if (!isset($_SESSION["user"])) {
    header('location:index.php');
    exit;
}

$q_total_income = mysqli_query($conn, "SELECT SUM(total) AS income FROM transaksi");
$total_income = mysqli_fetch_assoc($q_total_income)['income'] ?? 0;

$q_total_sold = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM detail_transaksi");
$total_sold = mysqli_fetch_assoc($q_total_sold)['total'] ?? 0;

$q_total_transaction = mysqli_query($conn, "SELECT * FROM transaksi");
$total_transaction = mysqli_num_rows($q_total_transaction);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin - Toko Dasha</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    body {
      background-color: #f1f3f5;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      position: fixed;
      height: 100%;
      background-color:rgb(34, 53, 71);
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

    .section-header {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .welcome-banner {
      background:rgb(53, 92, 128);
      color: white;
      border-radius: 0.5rem;
      padding: 1.5rem;
    }

    .card-stats {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 1rem;
      background-color: rgba(0, 0, 0, 0.05);
    }
  </style>
</head>

<body>

  <div class="container-fluid">
    <div class="row">
      <!-- sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-white mb-4"> Dashboard Dasha</h4>
        <hr style="color: white;">
        <nav class="nav flex-column">
          <a href="#" class="nav-link active">Dashboard</a>
          <a href="transaksi.php" class="nav-link">Transaksi</a>
          <a href="stok.php" class="nav-link">Manajemen Stok</a>
          <a href="laporan.php" class="nav-link">Laporan Penjualan</a>
          <a href="logout.php" class="nav-link text-danger mt-auto">Keluar</a>
        </nav>
      </div>

      <!-- konten utama -->
      <div class="col-md-10 offset-md-2 p-4">

        <!-- welcome banner -->
        <div class="welcome-banner mb-4">
          <h4 class="mb-1">Selamat datang kembali, <em><?php echo $_SESSION['user']?></em></h4>
          <p class="mb-0">Kelola toko kelontong Anda dengan mudah dan cepat.</p>
        </div>

        <!-- kartu info umum -->
        <div class="row g-5 justify-content-between">
          <div class="col-lg-4 col-sm-6 col-xs-12 mb-4 d-flex">
            <div class="card shadow h-100 w-100 text-white" style="background-color:rgb(29, 175, 0);">
              <div class="card-body card-stats">
                <div class="description">
                  <h5 class="card-title h4">Rp. <?php echo number_format($total_income, 0, ',', '.'); ?></h5>
                  <p class="card-text">Total Pendapatan</p>
                </div>
                <div class="icon bg-warning-light">
                  <i class="material-icons">payments</i>
                </div>
              </div>
              <div class="card-footer">
                <div class="card-footer-left">
                  <div class="icon me-2">
                    <i class="fas fa-chart-line"></i>
                  </div>
                  <p>+10%</p>
                </div>
                <div class="card-footer-right">
                  <p>Hari Ini</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-sm-6 col-xs-12 mb-4 d-flex">
            <div class="card shadow h-100 w-100 text-white" style="background-color: rgb(70, 98, 255);">
              <div class="card-body card-stats">
                <div class="description">
                  <h5 class="card-title h4"><?php echo number_format($total_sold, 0, ',', '.'); ?></h5>
                  <p class="card-text">Total Item Terjual</p>
                </div>
                <div class="icon bg-warning-light">
                  <i class="material-icons">local_shipping</i>
                </div>
              </div>
              <div class="card-footer">
                <div class="card-footer-left">
                  <div class="icon me-2">
                    <i class="fas fa-arrow-trend-up"></i>
                  </div>
                  <p>1%</p>
                </div>
                <div class="card-footer-right">
                  <p>Hari Ini</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-sm-6 col-xs-12 mb-4 d-flex">
            <div class="card shadow h-100 w-100 text-white" style="background-color:rgb(233, 124, 0);">
              <div class="card-body card-stats">
                <div class="description">
                  <h5 class="card-title h4"><?php echo number_format($total_transaction, 0, ',', '.'); ?></h5>
                  <p class="card-text">Total Transaksi</p>
                </div>
                <div class="icon bg-warning-light">
                  <i class="material-icons">receipt</i>
                </div>
              </div>
              <div class="card-footer">
                <div class="card-footer-left">
                  <div class="icon me-2">
                    <i class="fas fa-exclamation-circle"></i>
                  </div>
                  <p>+4%</p>
                </div>
                <div class="card-footer-right">
                  <p>Hari Ini</p>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- akses cepat -->
        <div class="mt-5">
          <div class="section-header">Akses Cepat</div>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="card shadow-sm p-4 justify-content-between">
                <div>
                  <h5 class="fw-bold">Tambah Stok Baru</h5>
                  <p class="text-muted">Catat stok baru ke dalam database dengan cepat.</p>
                </div>
                <a href="#" class="btn btn-lg mt-3 text-white fw-semibold" style="background-color: rgb(53, 92, 128)">Tambah Produk</a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card shadow-sm p-4 justify-content-between">
                <div>
                  <h5 class="fw-bold">Mulai Transaksi</h5>
                  <p class="text-muted">Masuk ke mode kasir untuk mencatat penjualan.</p>
                </div>
                <a href="transaksi.php" class="btn btn-lg mt-3 text-white fw-semibold" style="background-color: rgb(53, 92, 128)">Mulai
                  Transaksi</a>
              </div>
            </div>
          </div>
        </div>

        <!-- placeholder next fitur -->
        <div class="row mt-5">
          <div class="section-header">fitur tambahan</div>
          <div class="card shadow-sm">
            <div class="card-body">
              <p class="text-muted">Fitur tambahan nanti akan ditaruh di sini.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>