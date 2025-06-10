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

    $check = $conn->query("SELECT id FROM produk WHERE nama_produk = '".$conn->real_escape_string($nama)."'");
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Nama produk sudah ada.";
        header("Location: stok.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, stok, id_kategori, foto, aktif) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdiisi", $nama, $harga, $stok, $id_kategori, $foto, $aktif);
    $stmt->execute();

    $_SESSION['sukses'] = "Produk baru berhasil ditambahkan.";
    header("Location: stok.php");
    exit;
}

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