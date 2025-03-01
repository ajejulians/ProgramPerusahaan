<?php
$conn = new mysqli("localhost", "root", "", "gentamandiri");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil jumlah stok terbaru
$query = "SELECT jumlah_stok, tanggal_input FROM stokbarang ORDER BY tanggal_input DESC LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $jumlah_stok = $row['jumlah_stok'];
    $tanggal_input = $row['tanggal_input'];
} else {
    $jumlah_stok = "Data tidak tersedia";
    $tanggal_input = "-";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5">

    <title>Dashboard Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            color: #333;
        }
        p {
            font-size: 18px;
            font-weight: bold;
        }
        .nav {
            margin-top: 20px;
        }
        .nav a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
        }
        .nav a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Stok Barang</h2>
        <h2>Jumlah Stok Saat Ini:</h2>
        <p><strong><?php echo htmlspecialchars($jumlah_stok); ?></strong> unit</p>
        <p>Terakhir diperbarui: <?php echo htmlspecialchars($tanggal_input); ?></p>
        <div class="nav">
            <a href="stokbarang.php">➕ Tambah Stok</a>
            <a href="penjualan.php">🛒 Input Penjualan</a>
            <a href="gaji.php">💰 Lihat Gaji</a>
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="tambahkaryawan.php">➕ Tambah Karyawan</a>
            <a href="omzet.php">💵 Omzet</a>
        </div>
    </div>
</body>
</html>
