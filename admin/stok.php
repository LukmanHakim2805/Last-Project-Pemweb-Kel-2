<?php
include 'conn.php';

// Jika ada parameter `id`, tampilkan form restock untuk produk terkait
if (isset($_GET['id'])):
    $id = $_GET['id'];
    $data = $conn->query("SELECT * FROM produk WHERE id = $id")->fetch_assoc();
    ?>
    <h2>Restock Produk: <?= $data['nama_produk'] ?></h2>

    <form action="proses_restock.php" method="post">
        <input type="hidden" name="id_produk" value="<?= $data['id'] ?>">
        Jumlah Tambah: <input type="number" name="jumlah" required><br><br>
        Keterangan (opsional): <input type="text" name="keterangan"><br><br>
        ID Admin: <input type="number" name="id_admin" required><br><br>
        <input type="submit" value="Restock">
    </form>
    <a href="index.php">Kembali ke daftar</a>

<?php
// Jika ada parameter `log`, tampilkan riwayat restock
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
