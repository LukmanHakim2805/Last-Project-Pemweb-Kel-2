<?php
session_start();
include 'conn.php';

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Simulasi isi keranjang manual (jika belum diisi sebelumnya)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        1 => ['nama' => 'Contoh Produk A', 'harga' => 10000, 'jumlah' => 2, 'subtotal' => 20000],
        2 => ['nama' => 'Contoh Produk B', 'harga' => 15000, 'jumlah' => 1, 'subtotal' => 15000],
    ];
}

// Ambil daftar produk dari database
$produk_list = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaksi Kasir - Toko Dasha</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <h2 class="mb-4">🛒 Transaksi Kasir</h2>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="id_produk" class="form-label">Pilih Produk:</label>
            <select name="id_produk" id="id_produk" class="form-select" disabled>
                <option value="">-- Pilih Produk --</option>
                <?php while ($p = mysqli_fetch_assoc($produk_list)) : ?>
                    <option value="<?= $p['id'] ?>">
                        <?= htmlspecialchars($p['nama_produk']) ?> - <?= formatRupiah($p['harga']) ?> (Stok: <?= $p['stok'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah:</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" disabled />
        </div>

        <button type="submit" name="tambah_ke_keranjang" class="btn btn-primary" disabled>Tambah ke Keranjang</button>
    </form>

    <hr />

    <h3>Keranjang Belanja</h3>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $total += $item['subtotal'];
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td><?= formatRupiah($item['harga']) ?></td>
                        <td><?= $item['jumlah'] ?></td>
                        <td><?= formatRupiah($item['subtotal']) ?></td>
                        <td><button class="btn btn-danger btn-sm" disabled>Hapus</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>Total: <?= formatRupiah($total) ?></strong></p>

        <form method="POST">
            <div class="mb-3">
                <label for="pembayaran" class="form-label">Jumlah Pembayaran:</label>
                <input type="number" name="pembayaran" id="pembayaran" class="form-control" disabled />
            </div>
            <button type="submit" name="simpan_transaksi" class="btn btn-success" disabled>Simpan Transaksi</button>
        </form>

    <?php else: ?>
        <p>Keranjang kosong.</p>
    <?php endif; ?>

</body>
</html>
