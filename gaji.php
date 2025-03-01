<?php
$conn = new mysqli("localhost", "root", "", "gentamandiri");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Daftar gaji berdasarkan jumlah botol
$list_gaji = [
    30  => 120000,
    35  => 140000,
    45  => 180000,
    55  => 220000,
    65  => 290000,
    75  => 330000,
    85  => 380000,
    105 => 475000,
    115 => 515000,
    125 => 515000,
    155 => 690000
];

// Ambil parameter filter (hanya jika diisi)
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$search_name = $_GET['search_name'] ?? '';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Buat query dengan kondisi dinamis
$sql = "SELECT karyawan.nama_karyawan, COALESCE(SUM(penjualan.jumlah_penjualan), 0) AS penjualan_harian
        FROM karyawan
        LEFT JOIN penjualan ON karyawan.id = penjualan.id_karyawan";

$conditions = [];

// Tambahkan filter nama jika diisi
if (!empty($search_name)) {
    $conditions[] = "karyawan.nama_karyawan LIKE '%$search_name%'";
}

// Tambahkan filter tanggal jika diisi
if (!empty($start_date) && !empty($end_date)) {
    $conditions[] = "penjualan.tanggal BETWEEN '$start_date' AND '$end_date'";
}

// Gabungkan semua kondisi jika ada
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY karyawan.id, karyawan.nama_karyawan
          ORDER BY karyawan.nama_karyawan ASC
          LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Hitung total data
$total_result = $conn->query("SELECT COUNT(*) AS total FROM karyawan WHERE nama_karyawan LIKE '%$search_name%'");
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaji Karyawan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { width: 80%; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; position: sticky; top: 0; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { margin: 5px; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .pagination a:hover { background: #0056b3; }
        .reset { background: red; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Gaji Karyawan</h2>
    <form method="GET">
        <label>Filter Tanggal (Opsional): </label>
        <input type="date" name="start_date" value="<?= $start_date ?>"> -
        <input type="date" name="end_date" value="<?= $end_date ?>">
        <input type="text" name="search_name" placeholder="Cari Nama" value="<?= $search_name ?>">
        <button type="submit">Filter</button>
        <a href="gaji_karyawan.php" class="reset">Reset Filter</a>
    </form>
    <table>
        <tr>
            <th>Nama Karyawan</th>
            <th>Penjualan</th>
            <th>Gaji</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_karyawan']) ?></td>
                <td><?= $row['penjualan_harian'] ?> botol</td>
                <td>Rp <?= number_format($list_gaji[$row['penjualan_harian']] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&search_name=<?= $search_name ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>
<a href="index.php">🔙 Kembali ke Menu Utama</a>
</body>
</html>
