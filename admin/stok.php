<?php
session_start();
include 'conn.php';

$upload_dir = 'uploads/';

if (isset($_POST['tambah_produk'])) {
    $nama = trim($_POST['nama_produk']);
    $harga = (float)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $id_kategori = (int)$_POST['id_kategori'];

    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed)) {
            $gambar = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
        } else {
            $_SESSION['error'] = "Format gambar tidak didukung. Gunakan JPG, PNG, GIF.";
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

    $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, stok, id_kategori, gambar) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdiis", $nama, $harga, $stok, $id_kategori, $gambar);
    $stmt->execute();

    $_SESSION['sukses'] = "Produk baru berhasil ditambahkan.";
    header("Location: stok.php");
    exit;
}

if (isset($_POST['restock'])) {
    $id_produk = (int)$_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];
    $keterangan = trim($_POST['keterangan']);
    $id_admin = (int)$_POST['id_admin'];
    $tanggal = date('Y-m-d H:i:s');

    if ($jumlah < 1) {
        $_SESSION['error'] = "Jumlah restock harus lebih dari 0.";
        header("Location: stok.php?id=$id_produk");
        exit;
    }

    $conn->query("UPDATE produk SET stok = stok + $jumlah WHERE id = $id_produk");

    $stmt = $conn->prepare("INSERT INTO restock (id_produk, jumlah, keterangan, id_admin, tanggal) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisis", $id_produk, $jumlah, $keterangan, $id_admin, $tanggal);
    $stmt->execute();

    $_SESSION['sukses'] = "Produk berhasil di-restock.";
    header("Location: stok.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];

    $res = $conn->query("SELECT COUNT(*) AS total FROM detail_transaksi WHERE id_produk = $id");
    if ($res->fetch_assoc()['total'] > 0) {
        $_SESSION['error'] = "Produk tidak bisa dihapus karena masih ada transaksi terkait.";
        header("Location: stok.php");
        exit;
    }

    $res = $conn->query("SELECT COUNT(*) AS total FROM restock WHERE id_produk = $id");
    if ($res->fetch_assoc()['total'] > 0) {
        $_SESSION['error'] = "Produk tidak bisa dihapus karena masih ada data restock terkait.";
        header("Location: stok.php");
        exit;
    }

    $res = $conn->query("SELECT gambar FROM produk WHERE id = $id");
    $gambar = $res->fetch_assoc()['gambar'] ?? null;
    if ($gambar && file_exists($upload_dir . $gambar)) {
        unlink($upload_dir . $gambar);
    }

    $conn->query("DELETE FROM produk WHERE id = $id");

    $_SESSION['sukses'] = "Produk berhasil dihapus.";
    header("Location: stok.php");
    exit;
}

$result = $conn->query("SELECT produk.id, produk.nama_produk, produk.harga, produk.stok, produk.id_kategori, produk.gambar, kategori.nama_kategori FROM produk LEFT JOIN kategori ON produk.id_kategori = kategori.id ORDER BY produk.nama_produk");

$kategori_result = $conn->query("SELECT * FROM kategori");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Manajemen Produk & Stok - TOKO DASHA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        img.produk-gambar {
            max-width: 80px;
            max-height: 60px;
            object-fit: contain;
        }
    </style>
</head>
<body class="p-4 bg-light">

<div class="container">
    <h1 class="mb-4">Manajemen Produk & Stok</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">Tambah Produk Baru</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-select" required>
                        <option value="" disabled selected>Pilih kategori</option>
                        <?php while($kat = $kategori_result->fetch_assoc()): ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Harga</label>
                    <input type="number" step="0.01" name="harga" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" class="form-control" min="0" required />
                </div>
                <div class="mb-3">
                    <label>Gambar Produk (jpg, png, gif)</label>
                    <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.gif" />
                </div>
                <button type="submit" name="tambah_produk" class="btn btn-primary">Tambah Produk</button>
            </form>
        </div>
    </div>

    <h3>Daftar Produk</h3>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-primary">
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="6" class="text-center">Belum ada produk.</td></tr> <?php endif; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr> <td>
                        <?php if ($row['gambar'] && file_exists($upload_dir . $row['gambar'])): ?>
                            <img src="<?= $upload_dir . htmlspecialchars($row['gambar']) ?>" class="produk-gambar" alt="Gambar <?= htmlspecialchars($row['nama_produk']) ?>" />
                        <?php else: ?>
                            <small><i>Tidak ada gambar</i></small>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>
                        <a href="?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Restock</a>
                        <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')" >Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php

if (isset($_GET['id'])):
    $id = (int)$_GET['id'];
    $res = $conn->query("SELECT * FROM produk WHERE id = $id");
    if ($res->num_rows):
        $prod = $res->fetch_assoc();
    ?>
    <div class="card mt-5">
        <div class="card-header">Restock Produk: <?= htmlspecialchars($prod['nama_produk']) ?></div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id_produk" value="<?= $prod['id'] ?>" />
                <div class="mb-3">
                    <label>Jumlah Tambah</label>
                    <input type="number" name="jumlah" min="1" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Keterangan (opsional)</label>
                    <input type="text" name="keterangan" class="form-control" />
                </div>
                <div class="mb-3">
                    <label>ID Admin</label>
                    <input type="number" name="id_admin" class="form-control" required />
                </div>
                <button type="submit" name="restock" class="btn btn-primary">Restock</button>
            </form>
        </div>
    </div>
<?php endif; endif; ?>

</div>

</body>
</html>