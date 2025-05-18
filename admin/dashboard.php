<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Admin - Manajemen Toko</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .sidebar-custom {
      height: 100vh;
      background: linear-gradient(135deg, #222831, #393e46);
      color: #ffffff;
    }

    .sidebar-custom h4 {
      padding: 20px;
      border-bottom: 1px solid #444;
    }

    .sidebar-custom a {
      display: block;
      color: #fff;
      padding: 12px 20px;
      text-decoration: none;
      transition: background 0.3s;
    }

    .sidebar-custom a:hover {
      background-color: #00adb5;
    }

    .content-area {
      padding: 30px;
    }

    .card-custom {
      border: none;
      border-left: 5px solid #00adb5;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card-title {
      font-weight: 600;
    }

    .navbar-light {
      background-color: #ffffff;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 d-none d-md-block sidebar-custom">
        <h4 class="text-center">ADMIN TOKO</h4>
        <a href="#">Dashboard</a>
        <a href="#">Transaksi</a>
        <a href="#">Stok Barang</a>
        <a href="#"> Restock</a>
        <a href="#">Keluar</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 ms-sm-auto col-lg-10">
        <nav class="navbar navbar-light px-4">
          <span class="navbar-brand mb-0 h4">Dashboard Utama</span>
          <span class="text-muted">Selamat datang kembali, Admin!</span>
        </nav>

        <div class="content-area">
          <div class="row">
            <!-- Kartu Transaksi -->
            <div class="col-md-4 mb-4">
              <div class="card card-custom">
                <div class="card-body">
                  <h5 class="card-title">Total Transaksi Hari Ini</h5>
                  <p class="card-text fs-4 fw-bold text-success">Rp 2.500.000</p>
                  <p class="text-muted">35 transaksi berhasil</p>
                </div>
              </div>
            </div>

            <!-- Kartu Stok -->
            <div class="col-md-4 mb-4">
              <div class="card card-custom">
                <div class="card-body">
                  <h5 class="card-title">Jumlah Produk di Stok</h5>
                  <p class="card-text fs-4 fw-bold text-primary">128 item</p>
                  <p class="text-muted">Terupdate secara otomatis</p>
                </div>
              </div>
            </div>

            <!-- Kartu Restock -->
            <div class="col-md-4 mb-4">
              <div class="card card-custom">
                <div class="card-body">
                  <h5 class="card-title"> Restock</h5>
                  <p class="card-text fs-4 fw-bold text-warning">Sabun Mandi, Telur</p>
                  <p class="text-muted">Perlu ditindaklanjuti</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Area untuk grafik atau tabel -->
          <div class="row mt-5">
            <div class="col-md-12">
              <div class="card shadow-sm">
                <div class="card-header bg-white">
                  <strong>Riwayat Transaksi Terbaru</strong>
                </div>
                <div class="card-body">
                  <p class="text-muted">Belum ada data riwayat yang ditampilkan.</p>
                  <!-- Di sini bisa ditambahkan tabel transaksi -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- End Main Content -->
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
