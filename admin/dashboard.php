<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin - Toko Dasha</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f1f3f5;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      position:fixed;
      min-height: 100%;
      background-color: #212529;
      padding: 25px 15px;
    }

    .sidebar .nav-link {
      color: #adb5bd;
      border-radius: 5px;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background-color: #343a40;
      color: #fff;
    }

    .card-highlight {
      border-left: 4px solid #ffa500;
      background: #fff;
      box-shadow: 0 10px 5px rgba(0, 0, 0, 0.1);
    }

    .section-header {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .welcome-banner {
      background: linear-gradient(90deg, #ffa500, rgb(212, 142, 0));
      color: white;
      border-radius: 0.5rem;
      padding: 1.5rem;
    }
  </style>
</head>

<body>

  <div class="container-fluid">
    <div class="row">
      <!-- sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-white mb-4">TokoKu</h4>
        <nav class="nav flex-column">
          <a href="#" class="nav-link active">Dashboard</a>
          <a href="#" class="nav-link">Transaksi</a>
          <a href="#" class="nav-link">Manajemen Stok</a>
          <a href="laporan.php" class="nav-link">Laporan Penjualan</a>
          <a href="logout.php" class="nav-link text-danger mt-auto">Keluar</a>
        </nav>
      </div>

      <!-- konten utama -->
      <div class="col-md-10 offset-md-2 p-4">

        <!-- welcome banner -->
        <div class="welcome-banner mb-4">
          <h4 class="mb-1">Selamat datang kembali, Admin!</h4>
          <p class="mb-0">Kelola toko kelontong Anda dengan mudah dan cepat.</p>
        </div>

        <!-- kartu info umum -->
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card card-highlight p-3">
              <div class="d-flex justify-content-between">
                <div>
                  <div class="text-muted">Total Transaksi Hari Ini</div>
                  <div class="h5 fw-bold text-success">38</div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card card-highlight p-3">
              <div class="d-flex justify-content-between">
                <div>
                  <div class="text-muted">Total Pendapatan</div>
                  <div class="h5 fw-bold text-primary">Rp 900.000</div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card card-highlight p-3">
              <div class="d-flex justify-content-between">
                <div>
                  <div class="text-muted">Butuh Restock</div>
                  <div class="h5 fw-bold text-warning">Sabun Mandi, Telur</div>
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
              <div class="card shadow-sm p-4 d-flex flex-column justify-content-between">
                <div>
                  <h5 class="fw-bold">Tambah Stok Baru</h5>
                  <p class="text-muted">Catat stok baru ke dalam database dengan cepat.</p>
                </div>
                <a href="#" class="btn btn-lg mt-3 fw-semibold" style="background-color: #ffa500;">Tambah Produk</a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                  <h5 class="fw-bold">Mulai Transaksi</h5>
                  <p class="text-muted">Masuk ke mode kasir untuk mencatat penjualan.</p>
                </div>
                <a href="transaksi.php" class="btn btn-lg mt-3 fw-semibold" style="background-color: #ffa500">Mulai Transaksi</a>
              </div>
            </div>
          </div>
        </div>


        <!-- placeholder next fitur -->
        <div class="mt-5">
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