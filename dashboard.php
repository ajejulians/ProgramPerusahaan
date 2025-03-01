<?php
include 'koneksi.php';

// Ambil tanggal dari filter jika ada, jika tidak gunakan hari ini
$tanggal_filter = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Ambil semua karyawan
$query_karyawan = "SELECT id, nama_karyawan FROM karyawan";
$result_karyawan = $conn->query($query_karyawan);
$karyawan_list = [];
while ($row = $result_karyawan->fetch_assoc()) {
    $karyawan_list[$row['id']] = [
        'nama_karyawan' => $row['nama_karyawan'],
        'jumlah_terjual' => 0,
        'gaji_total' => 0
    ];
}

// Ambil data penjualan harian yang diakumulasi dalam minggu berjalan
$query_penjualan_mingguan = "SELECT id_karyawan, COALESCE(SUM(jumlah_penjualan), 0) AS jumlah_terjual 
                             FROM penjualan 
                             WHERE YEARWEEK(tanggal, 1) = YEARWEEK('$tanggal_filter', 1) 
                             AND tanggal <= '$tanggal_filter'
                             GROUP BY id_karyawan";
$result_penjualan_mingguan = $conn->query($query_penjualan_mingguan);

while ($row = $result_penjualan_mingguan->fetch_assoc()) {
    if (isset($karyawan_list[$row['id_karyawan']])) {
        $karyawan_list[$row['id_karyawan']]['jumlah_terjual'] = $row['jumlah_terjual'];
    }
}

// Ambil data gaji harian yang diakumulasi dalam minggu berjalan
$query_gaji_mingguan = "SELECT id_karyawan, COALESCE(SUM(gaji), 0) AS gaji_total 
                         FROM gaji_mingguan 
                         WHERE YEARWEEK(tanggal_pembayaran, 1) = YEARWEEK('$tanggal_filter', 1) 
                         AND tanggal_pembayaran <= '$tanggal_filter'
                         GROUP BY id_karyawan";
$result_gaji_mingguan = $conn->query($query_gaji_mingguan);

while ($row = $result_gaji_mingguan->fetch_assoc()) {
    if (isset($karyawan_list[$row['id_karyawan']])) {
        $karyawan_list[$row['id_karyawan']]['gaji_total'] = $row['gaji_total'];
    }
}

// Pagination
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total_rows = count($karyawan_list);
$total_pages = ceil($total_rows / $limit);
$displayed_karyawan = array_slice($karyawan_list, $offset, $limit, true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #007bff; color: white; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .pagination { text-align: center; margin-top: 10px; }
        .pagination a { padding: 8px 16px; margin: 2px; text-decoration: none; background-color: #007bff; color: white; border-radius: 5px; }
        .pagination a:hover { background-color: #0056b3; }
        select, input { padding: 8px; margin: 5px; }
        button { padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Dashboard Penjualan</h2>
    <form method="GET">
        <label for="tanggal">Pilih Tanggal:</label>
        <input type="date" name="tanggal" value="<?= $tanggal_filter ?>">
        <button type="submit">Filter</button>
    </form>
    <table>
        <tr>
            <th>Nama Karyawan</th>
            <th>Jumlah Terjual (Minggu Ini)</th>
            <th>Total Gaji (Minggu Ini)</th>
        </tr>
        <?php foreach ($displayed_karyawan as $data) { ?>
            <tr>
                <td><?= htmlspecialchars($data['nama_karyawan']) ?></td>
                <td><?= htmlspecialchars($data['jumlah_terjual']) ?> botol</td>
                <td>Rp <?= number_format($data['gaji_total'], 0, ',', '.') ?></td>
            </tr>
        <?php } ?>
    </table>

    <div class="pagination">
        <?php if ($page > 1) { ?>
            <a href="?page=<?= $page - 1 ?>&tanggal=<?= $tanggal_filter ?>">&laquo; Prev</a>
        <?php } ?>
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="?page=<?= $i ?>&tanggal=<?= $tanggal_filter ?>"><?= $i ?></a>
        <?php } ?>
        <?php if ($page < $total_pages) { ?>
            <a href="?page=<?= $page + 1 ?>&tanggal=<?= $tanggal_filter ?>">Next &raquo;</a>
        <?php } ?>
    </div>

    <a href="index.php">
        <button>⬅ Back to Home</button>
    </a>
</body>
</html>
