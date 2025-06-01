<?php
session_start();
include 'conn.php';

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Mulai sesi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tangani aksi tambah ke keranjang
if (isset($_POST['tambah_ke_keranjang'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];

    // Ambil data produk dari DB
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id_produk");
    $produk = mysqli_fetch_assoc($query);

    if ($produk && $jumlah > 0) {
        $_SESSION['cart'][$id_produk] = [
            'nama' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'jumlah' => $jumlah,
            'subtotal' => $produk['harga'] * $jumlah
        ];
        $pesan = "Produk berhasil ditambahkan ke keranjang.";
    } else {
        $error = "Gagal menambahkan produk.";
    }
}

// Ambil daftar produk dari database
$produk_list = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaksi Kasir - Tahap 2</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <h2 class="mb-4">🛒 Transaksi Kasir - Tahap 2</h2>

    <?php if (isset($pesan)): ?>
        <div class="alert alert-success"><?= $pesan ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

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
    <p>Fitur ini masih nonaktif pada tahap ini.</p>

</body>
</html>
