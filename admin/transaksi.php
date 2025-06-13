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
    $id_produk = (int)$_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];

    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id_produk");
    $produk = mysqli_fetch_assoc($query);

    if ($produk) {
        $jumlah_sebelumnya = $_SESSION['cart'][$id_produk]['jumlah'] ?? 0;
        $jumlah_total = $jumlah_sebelumnya + $jumlah;

        if ($jumlah_total > $produk['stok']) {
            $_SESSION['error'] = "Stok tidak cukup untuk produk <strong>" . htmlspecialchars($produk['nama_produk']) . "</strong>. Stok tersedia: {$produk['stok']}, diminta: {$jumlah_total}.";
        } else {
            $_SESSION['cart'][$id_produk] = [
                'nama' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'jumlah' => $jumlah_total,
                'subtotal' => $produk['harga'] * $jumlah_total
            ];
            $_SESSION['sukses'] = "Produk berhasil ditambahkan ke keranjang.";
        }
    }
    header("Location: transaksi.php");
    exit;
}

// Update jumlah
if (isset($_POST['update_jumlah'])) {
    $id_produk = (int)$_POST['update_id'];
    $jumlah_baru = (int)$_POST['jumlah_baru'];

    $query = mysqli_query($conn, "SELECT stok FROM produk WHERE id = $id_produk");
    $produk = mysqli_fetch_assoc($query);

    if ($produk) {
        if ($jumlah_baru > $produk['stok']) {
            $_SESSION['error'] = "Jumlah melebihi stok untuk produk ID $id_produk.";
        } elseif ($jumlah_baru < 1) {
            $_SESSION['error'] = "Jumlah tidak valid.";
        } else {
            $_SESSION['cart'][$id_produk]['jumlah'] = $jumlah_baru;
            $_SESSION['cart'][$id_produk]['subtotal'] = $_SESSION['cart'][$id_produk]['harga'] * $jumlah_baru;
            $_SESSION['sukses'] = "Jumlah berhasil diperbarui.";
        }
    }
    header("Location: transaksi.php");
    exit;
}

// Hapus dari keranjang
if (isset($_GET['hapus'])) {
    unset($_SESSION['cart'][(int)$_GET['hapus']]);
    header("Location: transaksi.php");
    exit;
}

// Simpan transaksi
if (isset($_POST['simpan_transaksi'])) {
    $tanggal = date('Y-m-d');
    $pembayaran = (float)$_POST['pembayaran'];
    $total = array_sum(array_column($_SESSION['cart'], 'subtotal'));
    $kembalian = $pembayaran - $total;

    if ($kembalian < 0) {
        $_SESSION['error'] = "Pembayaran tidak cukup!";
    } else {
        $id_admin = $_SESSION['id_admin'] ?? 1;

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

        $_SESSION['last_receipt'] = [
            'id_transaksi' => $id_transaksi,
            'total' => $total,
            'pembayaran' => $pembayaran,
            'kembalian' => $kembalian
        ];
        $_SESSION['cart'] = [];
        header("Location: transaksi.php?done=1");
        exit;
    }

    header("Location: transaksi.php");
    exit;
}

// Ambil daftar produk
$keyword = $_GET['cari'] ?? '';
$produk_list = mysqli_query($conn, $keyword ?
    "SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' ORDER BY nama_produk ASC" :
    "SELECT * FROM produk ORDER BY nama_produk ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card img { height: 120px; object-fit: contain; }
    </style>
</head>
<body class="p-4 bg-light">
<div class="container-fluid">
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-warning w-25">KEMBALI</a>
    </div>
    <div class="row">
        <!-- Kolom Produk -->
        <div class="col-lg-8">
            <form method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="cari" value="<?= htmlspecialchars($keyword) ?>" class="form-control" placeholder="Cari produk...">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </div>
            </form>

            <h3 class="mb-4">Pilih Produk</h3>
            <div class="row row-cols-2 row-cols-md-3 g-3">
                <?php while ($p = mysqli_fetch_assoc($produk_list)): ?>
                    <div class="col">
                        <form method="POST">
                            <input type="hidden" name="id_produk" value="<?= $p['id'] ?>">
                            <input type="hidden" name="jumlah" value="1">
                            <button type="submit" name="tambah_ke_keranjang" class="border-0 bg-transparent p-0 w-100 h-100">
                                <div class="card h-100 p-3">
                                    <img src="../assets/<?= $p['gambar'] ?? 'default.jpg' ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
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

        <!-- Kolom Keranjang -->
        <div class="col-lg-4">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['sukses'])): ?>
                <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
            <?php endif; ?>

            <div class="card mb-3">
                <div class="card-header bg-primary text-white">Keranjang Belanja</div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <table class="table table-sm">
                            <thead><tr><th>Produk</th><th>Jml</th><th>Sub</th><th></th></tr></thead>
                            <tbody>
                            <?php $total = 0;
                            foreach ($_SESSION['cart'] as $id => $item):
                                $total += $item['subtotal']; ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nama']) ?></td>
                                    <td>
                                        <form method="POST" class="d-flex">
                                            <input type="hidden" name="update_id" value="<?= $id ?>">
                                            <input type="number" name="jumlah_baru" value="<?= $item['jumlah'] ?>" min="1" class="form-control form-control-sm me-1" style="width:60px;">
                                            <button type="submit" name="update_jumlah" class="btn btn-sm btn-primary">⟳</button>
                                        </form>
                                    </td>
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
        </div>
    </div>
</div>

<!-- NOTIFIKASI TRANSAKSI SELESAI -->
<?php if (isset($_GET['done']) && isset($_SESSION['last_receipt'])): ?>
    <div class="position-fixed bottom-0 start-0 end-0 bg-success text-white p-3 shadow-lg">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>✅ Transaksi Berhasil!</strong><br>
                Total: <?= formatRupiah($_SESSION['last_receipt']['total']) ?>,
                Bayar: <?= formatRupiah($_SESSION['last_receipt']['pembayaran']) ?>,
                Kembali: <?= formatRupiah($_SESSION['last_receipt']['kembalian']) ?>
            </div>
            <div>
                <a href="cetak_nota.php?id=<?= $_SESSION['last_receipt']['id_transaksi'] ?>" target="_blank" class="btn btn-light btn-sm me-2">🖨️ Cetak Nota</a>
                <a href="transaksi.php" class="btn btn-outline-light btn-sm">Selesai</a>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['last_receipt']); ?>
<?php endif; ?>
</body>
</html>
