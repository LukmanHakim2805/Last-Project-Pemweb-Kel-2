<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Toko Dasha</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lexend+Mega:wght@100..900&display=swap" rel="stylesheet">
  <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet"> -->
</head>

<style>
  html {
    scroll-behavior: smooth;
    scroll-padding-top: 50px;
  }

  body {
    font-family: "Lexend Mega", sans-serif;
  }

  .navbar {
    background-color: rgb(0, 0, 0);
  }

  h1,
  h2,
  h3 {
    font-weight: 700;
  }

  .navbar .nav-link {
    color: #fff;
    opacity: 1;
  }

  .navbar .nav-link:hover {
    color: #e0e0e0 !important;
  }

  #beranda {
    background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.7)), url('assets/gmbr1.jpg') center/cover no-repeat;
    color: white;
  }
</style>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="/assets/logo.JPG" alt="Toko Dasha" height="40">
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
          <h1 class="display-6 fw-bold">Selamat Datang di TOKO DASHA</h1>
          <p class="fs-5">Toko terbaik untuk kebutuhan harian Anda! Temukan berbagai macam produk berkualitas dengan
            harga terjangkau. Kami buka setiap hari untuk melayani kebutuhan Anda.</p>
          <p class="fs-6">Jam Operasional:<br>Senin – Minggu, 07.00 – 21.00 WIB</p>
        </div>
        <div class="col-md-6 text-center">
          <img src="assets/gmbr2.jpg" alt="Welcome" class="img-fluid rounded">
        </div>
      </div>
    </div>
  </section>

  <!-- Produk Section -->
  <section id="produk" class="py-5" style="background: #fabd42">
    <div class="container">
      <h1 class="text-center text-black">Produk Kami</h1>

      <div class="row align-items-center">
        <div class="col-md-4">
          <img src="assets/gmbr-sembako.png" alt="Produk 1" class="img-fluid rounded">
        </div>
        <div class="col-md-8">
          <h2>Sembako</h2>
          <p class="fs-5">Kami menyediakan berbagai kebutuhan pokok seperti beras, gula, minyak goreng, mie instan, dan
            produk sembako lainnya dengan harga terjangkau dan kualitas terbaik.</p>
        </div>
      </div>
      <hr class="mb-5" style="height: 1px; border: 0; background-color: black; opacity: 1;">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h2>Gas Elpiji & Air Galon</h2>
          <p class="fs-5">Tersedia Gas LPG 3kg dan 12kg serta air galon isi ulang. Layanan antar tersedia untuk
            mempermudah Anda memenuhi kebutuhan rumah tangga.</p>
        </div>
        <div class="col-md-4">
          <img src="assets/gmbr-galon.png" alt="Produk 2" class="img-fluid rounded" style="width: 80%;">
        </div>
      </div>
    </div>
  </section>


  <!-- Galeri Section -->
  <section id="galeri" class="py-5 bg-light">
    <div class="container">
      <h1 class="text-center mb-4 text-black">Galeri</h1>
      <div class="row mb-4">
        <div class="col-md-4 mb-4">
          <div class="card border border-3 border-dark" style="max-width: 350px; max-height:270px; overflow: hidden;">
            <img src="assets/gmbr1.jpg" alt="Galeri 1">
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card border border-3 border-dark" style="max-width: 350px; max-height:270px; overflow: hidden;">
            <img src="assets/gmbr2.jpg" alt="Galeri 2">
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card border border-3 border-dark" style="max-width: 350px; max-height:270px; overflow: hidden;">
            <img src="assets/gmbr3.jpg" style="width: 100%; height: 100%;" alt="Galeri 3">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4 justify-content-center d-flex">
          <div class="card border border-3 border-dark" style="max-width: 500px; max-height:270px; overflow: hidden;">
            <img src="assets/gmbr2.jpg" alt="Galeri 4">
          </div>
        </div>
        <div class="col-md-6 mb-4 justify-content-center d-flex">
          <div class="card border border-3 border-dark" style="max-width: 500px; max-height:270px; overflow: hidden;">
            <img src="assets/gmbr1.jpg" alt="Galeri 5">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="kontak" class="py-5" style="background: #fabd42">
    <div class="container">
      <h1 class="text-center mb-4 text-black">Lokasi & Kontak</h1>
      <div class="row">
        <div class="col-md-8 mb-4">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3349.2253616285007!2d112.80366018147579!3d-7.327078470189006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fb0e7cbc9457%3A0xd7881c722aa4dc45!2sPawon%20Dasha!5e1!3m2!1sid!2sid!4v1749880462508!5m2!1sid!2sid"
            width="700" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="col-md-4 fs-5">
          <h4 class="fs-3"><strong>Hubungi Kami!</strong></h4>
          <p class="fs-5">Silakan hubungi kami untuk pertanyaan, pemesanan produk, atau informasi mengenai layanan kami.
          </p>
          <p><strong>Alamat:</strong><span class="fs-6"> Jl. Tambak Medokan Ayu X No.20, Medokan Ayu, Kec. Rungkut, Surabaya, Jawa Timur 60295</span></p>
          <p><strong>Jam Operasional:</strong><span class="fs-6"> Senin – Minggu, 07.00 – 21.00 WIB</span></p>
          <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success mt-3 w-100">
            <img src="https://cdn-icons-png.flaticon.com/24/733/733585.png" alt="WhatsApp" class="me-2">
            Chat via WhatsApp
          </a>
        </div>
      </div>
    </div>
  </section>

  <footer class="text-center p-3 bg-black text-white">
    © 2025 TOKO DASHA. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>