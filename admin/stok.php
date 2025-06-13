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

    // Cek di detail_transaksi
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

    // Hapus foto dulu jika ada
    $res = $conn->query("SELECT foto FROM produk WHERE id = $id");
    $foto = $res->fetch_assoc()['foto'] ?? null;
    if ($foto && file_exists($upload_dir . $foto)) {
        unlink($upload_dir . $foto);
    }

    $conn->query("DELETE FROM produk WHERE id = $id");

    $_SESSION['sukses'] = "Produk berhasil dihapus.";
    header("Location: stok.php");
    exit;
}