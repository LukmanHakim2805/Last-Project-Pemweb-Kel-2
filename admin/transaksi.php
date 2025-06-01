<?php
session_start();
include 'conn.php';

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah ke keranjang
if (isset($_POST['tambah_ke_keranjang'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];

    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id_produk");
    $produk = mysqli_fetch_assoc($query);

    if ($produk) {
        if (isset($_SESSION['cart'][$id_produk])) {
            $_SESSION['cart'][$id_produk]['jumlah'] += $jumlah;
            $_SESSION['cart'][$id_produk]['subtotal'] = $_SESSION['cart'][$id_produk]['harga'] * $_SESSION['cart'][$id_produk]['jumlah'];
        } else {
            $_SESSION['cart'][$id_produk] = [
                'nama' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'jumlah' => $jumlah,
                'subtotal' => $produk['harga'] * $jumlah
            ];
        }
    }
}

// Hapus item dari keranjang
if (isset($_GET['hapus'])) {
    $hapus_id = (int)$_GET['hapus'];
    unset($_SESSION['cart'][$hapus_id]);
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
            <select name="id_produk" id="id_produk" class="form-select" required>
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
            <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required />
        </div>

        <button type="submit" name="tambah_ke_keranjang" class="btn btn-primary">Tambah ke Keranjang</button>
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
                foreach ($_SESSION['cart'] as $id => $item):
                    $total += $item['subtotal'];
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td><?= formatRupiah($item['harga']) ?></td>
                        <td><?= $item['jumlah'] ?></td>
                        <td><?= formatRupiah($item['subtotal']) ?></td>
                        <td>
                            <a href="?hapus=<?= $id ?>" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
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
