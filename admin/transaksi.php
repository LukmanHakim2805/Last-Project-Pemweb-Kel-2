<?php
session_start();
include 'conn.php';

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['tambah_ke_keranjang'])) {
    $id_produk = (int)$_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];

    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id_produk");
    $produk = mysqli_fetch_assoc($query);

    if ($produk) {
        $jumlah_sebelumnya = isset($_SESSION['cart'][$id_produk]) ? $_SESSION['cart'][$id_produk]['jumlah'] : 0;
        $jumlah_total = $jumlah_sebelumnya + $jumlah;

        if ($jumlah_total > $produk['stok']) {
            $error = "Stok tidak cukup untuk produk <strong>" . htmlspecialchars($produk['nama_produk']) . "</strong>. Stok tersedia: {$produk['stok']}, diminta: {$jumlah_total}.";
        } else {
            $_SESSION['cart'][$id_produk] = [
                'nama' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'jumlah' => $jumlah_total,
                'subtotal' => $produk['harga'] * $jumlah_total
            ];
        }
    }
}

if (isset($_GET['hapus'])) {
    $hapus_id = (int)$_GET['hapus'];
    unset($_SESSION['cart'][$hapus_id]);
    header("Location: transaksi.php");
    exit;
}

if (isset($_POST['simpan_transaksi'])) {
    $tanggal = date('Y-m-d');
    $pembayaran = (float)$_POST['pembayaran'];
    $total = array_sum(array_column($_SESSION['cart'], 'subtotal'));
    $kembalian = $pembayaran - $total;

    if ($kembalian < 0) {
        $error = "Pembayaran tidak cukup!";
    } else {
        $stmt = $conn->prepare("INSERT INTO transaksi (tanggal, total, pembayaran, kembalian, id_admin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdddi", $tanggal, $total, $pembayaran, $kembalian, $id_admin);
        $stmt->execute();
        $id_transaksi = $stmt->insert_id;

        foreach ($_SESSION['cart'] as $id_produk => $item) {
            $jumlah = $item['jumlah'];
            $harga = $item['harga'];
            $subtotal = $item['subtotal'];

            $conn->query("INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan, subtotal) 
                          VALUES ($id_transaksi, $id_produk, $jumlah, $harga, $subtotal)");

            $conn->query("UPDATE produk SET stok = stok - $jumlah WHERE id = $id_produk");
        }

        $_SESSION['cart'] = [];
        $sukses = "Transaksi berhasil disimpan. Kembalian: " . formatRupiah($kembalian);
    }
}

$produk_list = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk ASC");
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaksi Kasir - Toko Dasha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
    }
    .card img {
        height: 120px;
        object-fit: contain;
    }
</style>
</head>
<body class="p-4 bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Daftar Produk -->
        <div class="col-lg-8">
            <h3 class="mb-4">Pilih Produk</h3>
            <div class="row row-cols-2 row-cols-md-3 g-3">
    <?php mysqli_data_seek($produk_list, 0); ?>
    <?php while ($p = mysqli_fetch_assoc($produk_list)) : ?>
        <div class="col">
            <form method="POST" class="klik-card">
                <input type="hidden" name="id_produk" value="<?= $p['id'] ?>">
                <input type="hidden" name="jumlah" value="1">
                <button type="submit" name="tambah_ke_keranjang" class="border-0 bg-transparent p-0 w-100 h-100">
                    <div class="card h-100 klik-area">
                        <img src="path-to-images/<?= $p['gambar'] ?? 'default.jpg' ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                        <div class="card-body text-center">
                            <h6 class="card-title"><?= htmlspecialchars($p['nama_produk']) ?></h6>
                            <p class="text-primary fw-bold mb-0"><?= formatRupiah($p['harga']) ?></p>
                        </div>
                    </div>
                </button>
            </form>
        </div>
    <?php endwhile; ?>
</div>

        </div>

        <!-- Panel Samping -->
        <div class="col-lg-4">
            <!-- Petunjuk -->
            <div class="alert alert-success">
                <h6><i class="bi bi-info-circle"></i> Petunjuk</h6>
                <ul class="mb-0 ps-3">
                    <li>Pilih produk dari daftar</li>
                    <li>Klik ikon roda untuk atur harga</li>
                    <li>Klik nama customer untuk ubah nama</li>
                    <li>Klik ikon hapus untuk reset keranjang</li>
                </ul>
            </div>

            <!-- Keranjang -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">🧺 Keranjang Belanja</div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jml</th>
                                    <th>Sub</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($_SESSION['cart'] as $id => $item):
                                    $total += $item['subtotal'];
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['nama']) ?></td>
                                        <td><?= $item['jumlah'] ?></td>
                                        <td><?= formatRupiah($item['subtotal']) ?></td>
                                        <td><a href="?hapus=<?= $id ?>" class="btn btn-sm btn-danger">✕</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p class="fw-bold">Total: <?= formatRupiah($total) ?></p>

                        <form method="POST">
                            <input type="number" name="pembayaran" class="form-control mb-2" min="<?= $total ?>" placeholder="Pembayaran" required />
                            <button type="submit" name="simpan_transaksi" class="btn btn-success w-100">Bayar</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted">Keranjang kosong.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notifikasi -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if (isset($sukses)): ?>
                <div class="alert alert-success"><?= $sukses ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>


</html>
