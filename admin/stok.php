<?php
session_start();
include 'conn.php';

$upload_dir = 'uploads/';

if (isset($_POST['tambah_produk'])) {
    $nama = trim($_POST['nama_produk']);
    $harga = (float)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $id_kategori = (int)$_POST['id_kategori'];
    $aktif = 1; // default aktif

    // Upload foto jika ada
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed)) {
            $foto = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $foto);
        } else {
            $_SESSION['error'] = "Format foto tidak didukung. Gunakan JPG, PNG, GIF.";
            header("Location: stok.php");
            exit;
        }
    }

<?php
// Jika ada parameter log, tampilkan riwayat restock
elseif (isset($_GET['log'])):
    $query = "
        SELECT r.*, p.nama_produk, a.username 
        FROM restock r
        LEFT JOIN produk p ON r.id_produk = p.id
        LEFT JOIN admin a ON r.id_admin = a.id
        ORDER BY r.tanggal DESC
    ";
    $result = $conn->query($query);
    ?>

    <h2>Riwayat Restock</h2>
    <a href="index.php">Kembali ke daftar</a>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
            <th>Admin</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['nama_produk'] ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td><?= $row['keterangan'] ?></td>
            <td><?= $row['username'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

<?php
// Default: tampilkan daftar produk
else:
    $result = $conn->query("SELECT produk.*, kategori.nama_kategori FROM produk LEFT JOIN kategori ON produk.id_kategori = kategori.id");
    ?>
    <h2>Daftar Produk - TOKO DASHA</h2>
    <a href="?log=1">Lihat Riwayat Restock</a>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['nama_produk'] ?></td>
            <td><?= $row['nama_kategori'] ?></td>
            <td><?= number_format($row['harga'], 2) ?></td>
            <td><?= $row['stok'] ?></td>
            <td><a href="?id=<?= $row['id'] ?>">Restock</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>