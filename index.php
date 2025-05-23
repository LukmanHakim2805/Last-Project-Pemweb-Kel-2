<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>WARUNG ANU</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
</head>

<style>
  html{
    scroll-behavior: smooth;
    scroll-padding-top: 50px;
  }

  body {
    font-family: 'Nunito', sans-serif;
    min-height: 2000px;
  }

  .navbar {
    font-family: 'Merriweather', sans-serif;
    background-color:rgb(0, 0, 0);
  }

  h1, h2, h3 {
    font-family: 'Merriweather', sans-serif;
    font-weight: 700;
  }

  .navbar .nav-link {
    color: #fff !important;
    opacity: 1 !important;
  }
  .navbar .nav-link:hover {
    color: #e0e0e0 !important;
  }

  #beranda {
    background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.9), rgba(0,0,0,0.7)), url('assets/welcome.jpg') center/cover no-repeat;
    color: white;
  }
</style>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="your-logo.png" alt="Menika Logo" height="40">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end " id="navbarNav">
        <ul class="navbar-nav gap-5">
          <li class="nav-item">
            <a class="nav-link" href="#produk">Produk</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#galeri">Galeri</a>
          </li>
          <li class="nav-item me-5">
            <a class="nav-link" href="#kontak">Kontak</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Welcome Section -->
  <section id="beranda" class="py-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="display-5 fw-bold">Selamat Datang di TOKO ANU</h1>
          <p class="fs-4">Toko terbaik untuk kebutuhan harian Anda! Temukan berbagai macam produk berkualitas dengan harga terjangkau. Kami buka setiap hari untuk melayani kebutuhan Anda.</p>
          <p class="fs-5">Jam Operasional:<br>Senin – Minggu, 09.00 – 21.00 WIB</p>
        </div>
        <div class="col-md-6 text-center">
          <img src="assets/welcome.jpg" alt="Welcome" class="img-fluid rounded">
        </div>
      </div>
    </div>
  </section>

  <!-- Produk Section -->
  <section id="produk" class="py-5" style="background: #fabd42">
    <div class="container">
      <h1 class="text-center mb-4"><strong>Produk Kami</strong></h1>
      <div class="row mb-4">
        <div class="col-md-4">
          <img src="assets/welcome.jpg" alt="Produk 1" class="img-fluid rounded mb-5">
        </div>
        <div class="col-md-8">
          <h2>Sembako</h2>
          <p class="fs-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui id blanditiis aspernatur eligendi molestiae debitis nesciunt temporibus hic vitae quo eius aliquid iste minus sunt reprehenderit delectus, veniam nemo recusandae.</p>
        </div>

       <div class="row mb-4">
        <div class="col-md-8">
          <h2>Gas Elpiji & Galon</h2>
          <p class="fs-5">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Id dolorem corrupti unde vitae dolores iure vero ullam at inventore eius sed placeat soluta eos nesciunt ad culpa aut, accusantium reiciendis!</p>
        </div>
        <div class="col-md-4">
          <img src="assets/welcome.jpg" alt="Produk 2" class="img-fluid rounded mb-5">
        </div>
      </div>
    </div>
  </section>

  <!-- Galeri Section -->
  <section id="galeri" class="py-5 bg-light">
    <div class="container">
      <h1 class="text-center mb-4">Galeri</h1>
      <div class="row mb-4">
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="assets/welcome.jpg" class="card-img-top" alt="Galeri 1">
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="assets/welcome.jpg" class="card-img-top" alt="Galeri 2">
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="assets/welcome.jpg" class="card-img-top" alt="Galeri 3">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card">
            <img src="assets/welcome.jpg" class="card-img-top" alt="Galeri 4">
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card">
            <img src="assets/welcome.jpg" class="card-img-top" alt="Galeri 5">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="kontak" class="py-5" style="background: #fabd42">
    <div class="container">
      <h1 class="text-center mb-4">Lokasi & Kontak</h1>
      <div class="row">
        <div class="col-md-8 mb-4">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3349.1754100977296!2d112.78832469999999!3d-7.333721199999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fab87edcad15%3A0xb26589947991eea1!2sUniversitas%20Pembangunan%20Nasional%20%22Veteran%22%20Jawa%20Timur!5e1!3m2!1sid!2sid!4v1747570116074!5m2!1sid!2sid" width="700" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="col-md-4 fs-5">
          <h4 class="fs-3"><strong>Hubungi Kami!</strong></h4>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam cumque provident a aliquam quos ab voluptatibus unde ullam soluta dicta enim corporis velit perferendis, fugiat iusto suscipit nesciunt ipsa eum!</p>
          <p>Alamat: Cedake UPN No. xxx</p>
          <p>Jam Buka: Senin – Minggu, 09.00 – 21.00 WIB</p>
        </div>
      </div>
    </div>
  </section>

<footer class="text-center p-3 bg-black text-white">
    © 2025 WARUNG ANU. All rights reserved.
</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
