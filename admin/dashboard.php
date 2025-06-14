<?php
require 'conn.php';
session_start();

if (!isset($_SESSION["user"])) {
    header('location:index.php');
    exit;
}

// query untuk card info umum
$q_total_income = mysqli_query($conn, "SELECT SUM(total) AS income FROM transaksi");
$total_income = mysqli_fetch_assoc($q_total_income)['income'] ?? 0;

$q_total_sold = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM detail_transaksi");
$total_sold = mysqli_fetch_assoc($q_total_sold)['total'] ?? 0;

$q_total_transaction = mysqli_query($conn, "SELECT * FROM transaksi");
$total_transaction = mysqli_num_rows($q_total_transaction);


// query untuk line chart
// Query minggu ini
$day_labels_this_week = [];
$day_counts_this_week = [];

$q_this_week = mysqli_query($conn, "SELECT DAYNAME(tanggal) AS hari, COUNT(*) AS jumlah 
                                    FROM transaksi 
                                    WHERE YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)
                                    GROUP BY DAYOFWEEK(tanggal)");

while ($row = mysqli_fetch_assoc($q_this_week)) {
    $day_labels_this_week[] = $row['hari'];
    $day_counts_this_week[] = (int)$row['jumlah'];
}

// Query minggu lalu
$day_labels_last_week = [];
$day_counts_last_week = [];

$q_last_week = mysqli_query($conn, "SELECT DAYNAME(tanggal) AS hari, COUNT(*) AS jumlah 
                                    FROM transaksi 
                                    WHERE YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE() - INTERVAL 7 DAY, 1)
                                    GROUP BY DAYOFWEEK(tanggal)");

while ($row = mysqli_fetch_assoc($q_last_week)) {
    $day_labels_last_week[] = $row['hari'];
    $day_counts_last_week[] = (int)$row['jumlah'];
}

// Cek nilai minimal stok dari input user atau default 10
$min_stok = isset($_GET['min_stok']) ? (int)$_GET['min_stok'] : 10;
$q_restock = mysqli_query($conn, "SELECT produk.nama_produk, kategori.nama_kategori, produk.stok FROM produk JOIN kategori ON produk.id_kategori = kategori.id WHERE produk.stok < $min_stok");
$produk_restock = [];
while ($row = mysqli_fetch_assoc($q_restock)) {
  $produk_restock[] = $row;
}
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

    /* .section-header {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1rem;
    } */

    .welcome-banner {
      color: white;
      border-radius: 0.5rem;
      padding: 1.5rem;
      box-shadow: 5px 5px 20px rgb(160, 160, 160);
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
        <div class="welcome-banner mb-4 bg-primary">
          <h4 class="mb-1">Selamat datang kembali, <em><?php echo $_SESSION['user']?></em></h4>
          <p class="mb-0">Kelola toko kelontong Anda dengan mudah dan cepat.</p>
        </div>

        <!-- card info umum -->
        <div class="row g-5 justify-content-between">
          <div class="col-lg-4 col-sm-6 col-xs-12 d-flex">
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

          <div class="col-lg-4 col-sm-6 col-xs-12 d-flex">
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

          <div class="col-lg-4 col-sm-6 col-xs-12 d-flex">
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
        <div class="mt-4">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="card shadow-sm p-4 justify-content-between">
                <div>
                  <h5 class="fw-bold">Lihat Laporan</h5>
                  <p class="text-muted">Monitor data penjualan dan lainnya dengan efektif.</p>
                </div>
                <a href="laporan.php" class="btn btn-lg mt-3 text-white fw-semibold bg-primary">Laporan</a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card shadow-sm p-4 justify-content-between">
                <div>
                  <h5 class="fw-bold">Mulai Transaksi</h5>
                  <p class="text-muted">Masuk ke mode kasir untuk mencatat penjualan.</p>
                </div>
                <a href="transaksi.php" class="btn btn-lg mt-3 text-white fw-semibold bg-primary">Mulai
                  Transaksi</a>
              </div>
            </div>
          </div>
        </div>

        <!-- grafik -->
        <div class="mt-5">
          <div class="row">
            <!-- line chart transaksi -->
            <div class="col-md-12 mb-4">
              <div class="card shadow-sm p-4" style="height: 400px;">
                <h5 class="fw-bold mb-3">Transaksi</h5>
                <canvas id="dailyChart" height="300"></canvas>
              </div>
            </div>

        <!-- <div class="row">
            <div class="card shadow-sm p-4">
              <h5 class="fw-bold mb-3">Produk Butuh Restok</h5>
                -dropdown untuk menentukan stok minimal
                -tabel untuk menunjukkan barang dengan stok dibawah minimal yang telah ditentukan
            </div>
          </div> -->

        <div class="mt-3">
          <div class="card shadow-sm p-4">
            <h4 class="mb-3">Produk Butuh Restok</h4>
            <form method="get" class="row g-3 align-items-center mb-3">
              <div class="col-auto">
                <label for="min_stok" class="col-form-label">Stok Minimal:</label>
              </div>
              <div class="col-auto">
                <input type="number" class="form-control" name="min_stok" id="min_stok"
                  value="<?= htmlspecialchars($min_stok) ?>" min="1" required>
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary">Terapkan</button>
              </div>
            </form>

            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="table-light">
                  <tr>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($produk_restock) > 0): ?>
                  <?php foreach ($produk_restock as $produk): ?>
                  <tr>
                    <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($produk['nama_kategori']) ?></td>
                    <td><?= (int)$produk['stok'] ?></td>
                  </tr>
                  <?php endforeach; ?>
                  <?php else: ?>
                  <tr>
                    <td colspan="3" class="text-center text-muted">Tidak ada produk di bawah stok minimal.</td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <a href="stok.php" class="btn btn-primary fs-5 fw-bold" style="height: 40px;">+ Restok Sekarang</a>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  
  const labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
  const thisWeekData = [12, 19, 3, 5, 2, 3, 7];
  const lastWeekData = [8, 14, 5, 2, 3, 7, 4];

const ctx1 = document.getElementById('dailyChart').getContext('2d');
const dailyChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: labels<?php //echo json_encode($day_labels_this_week); ?>,
        datasets: [
            {
                label: 'Minggu Ini',
                // data: <?php //echo json_encode($day_counts_this_week); ?>,
                data: thisWeekData,
                backgroundColor: 'rgba(53, 92, 128, 0.7)',
                borderColor: 'rgba(53, 92, 128, 1)',
                borderWidth: 2,
                tension: 0.1
            },
            {
                label: 'Minggu Lalu',
                //data: <?php //echo json_encode($day_counts_last_week); ?>,
                data: lastWeekData,
                backgroundColor: 'rgba(230, 126, 34, 0.7)',
                borderColor: 'rgba(230, 126, 34, 1)',
                borderWidth: 2,
                tension: 0.1
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});


  </script>

</body>

</html>