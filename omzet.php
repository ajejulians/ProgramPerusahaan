<?php
include 'koneksi.php';

// Ambil total botol terjual dalam 7 hari terakhir
$result = mysqli_query($conn, "SELECT SUM(jumlah_penjualan) AS total_terjual FROM penjualan WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$row = mysqli_fetch_assoc($result);
$total_terjual = $row['total_terjual'] ?? 0;

// Hitung jumlah karyawan yang aktif dalam 7 hari terakhir
$result_karyawan = mysqli_query($conn, "SELECT COUNT(DISTINCT id_karyawan) AS total_karyawan FROM penjualan WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$row_karyawan = mysqli_fetch_assoc($result_karyawan);
$total_karyawan = $row_karyawan['total_karyawan'] ?? 1; // Minimal 1 agar tidak terjadi pembagian dengan nol

// Harga jual per botol
$harga_per_botol = 10000;

// Hitung omzet
$omzet = $total_terjual * $total_karyawan * $harga_per_botol;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung Omzet</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 20px;
        background-color: #f4f4f4;
    }
    .container {
        width: 50%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
    }
    p {
        font-size: 20px;
        text-align: center;
    }
    .back-button {
        display: block;
        width: fit-content;
        margin: 20px auto;
        padding: 10px 20px;
        text-decoration: none;
        background: #dc3545;
        color: white;
        border-radius: 5px;
        text-align: center;
    }
    .back-button:hover {
        background-color: #c82333;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Total Omzet Penjualan (7 Hari Terakhir)</h2>
    <p>Rp <?= number_format($omzet, 0, ',', '.'); ?></p>
    <a href="index.php" class="back-button">🔙 Kembali ke Menu Utama</a>
</div>
</body>
</html>
    