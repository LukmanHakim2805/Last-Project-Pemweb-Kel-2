<?php
session_start();
include 'cnn.php'; // file koneksi database

// Default: laporan hari ini
$filter = $_GET['filter'] ?? 'harian';
$today = date('Y-m-d');
$startDate = $today;
$endDate = $today;

switch ($filter) {
    case 'mingguan':
        $startDate = date('Y-m-d', strtotime('-6 days')); // 7 hari termasuk hari ini
        break;
    case 'bulanan':
        $startDate = date('Y-m-01');
        break;
}

// Ambil data transaksi
$query = $conn->prepare("SELECT tanggal, SUM(total) as total, SUM(pembayaran) as pembayaran, SUM(kembalian) as kembalian, id_admin 
                         FROM transaksi 
                         WHERE tanggal BETWEEN ? AND ?
                         GROUP BY tanggal ORDER BY tanggal ASC");
$query->bind_param("ss", $startDate, $endDate);
$query->execute();
$result = $query->get_result();

$totalPendapatan = 0;
$jumlahTransaksi = 0;
$data = [];
$chartLabels = [];
$chartData = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    $totalPendapatan += $row['total'];
    $jumlahTransaksi++;

    $chartLabels[] = $row['tanggal'];
    $chartData[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Laporan Penjualan - <?= ucfirst($filter) ?></h2>

<form method="GET" action="">
    <label>Filter:</label>
    <select name="filter" onchange="this.form.submit()">
        <option value="harian" <?= $filter == 'harian' ? 'selected' : '' ?>>Harian</option>
        <option value="mingguan" <?= $filter == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
        <option value="bulanan" <?= $filter == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
    </select>
</form>

<p>Total Transaksi: <?= $jumlahTransaksi ?></p>
<p>Total Pendapatan: Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></p>

<!-- Tabel Riwayat Transaksi -->
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Tanggal</th>
        <th>Total</th>
        <th>Pembayaran</th>
        <th>Kembalian</th>
        <th>Admin</th>
    </tr>
    <?php foreach ($data as $t): ?>
        <tr>
            <td><?= $t['tanggal'] ?></td>
            <td>Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($t['pembayaran'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($t['kembalian'], 0, ',', '.') ?></td>
            <td><?= $t['id_admin'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Grafik Penjualan -->
<h3>Grafik Penjualan</h3>
<canvas id="salesChart" width="600" height="300"></canvas>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: <?= json_encode($chartData) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>

<!-- Tombol Ekspor -->
<br>
<a href="export_pdf.php?filter=<?= $filter ?>">Export PDF</a> |
<a href="export_excel.php?filter=<?= $filter ?>">Export Excel</a>

</body>
</html>
