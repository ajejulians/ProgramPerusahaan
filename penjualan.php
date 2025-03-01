<?php
$conn = new mysqli("localhost", "root", "", "gentamandiri");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil daftar karyawan dari database
$query_karyawan = "SELECT id, nama_karyawan FROM karyawan";
$result_karyawan = $conn->query($query_karyawan);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_karyawan = (int) $_POST['id_karyawan'];
    $jumlah_terjual = (int) $_POST['jumlah_terjual'];

    // Ambil stok barang saat ini
    $query = "SELECT jumlah_stok FROM stokbarang LIMIT 1";
    $result = $conn->query($query);
    $stok_sekarang = $result->fetch_assoc()['jumlah_stok'];

    if ($jumlah_terjual > $stok_sekarang) {
        echo "Stok tidak mencukupi!";
    } else {
        // Insert penjualan ke database
        $sql = "INSERT INTO penjualan (id_karyawan, jumlah_penjualan, tanggal) VALUES ($id_karyawan, $jumlah_terjual, CURDATE())";
        $conn->query($sql);

        // Update stok barang
        $stok_baru = $stok_sekarang - $jumlah_terjual;
        $conn->query("UPDATE stokbarang SET jumlah_stok = $stok_baru");

        echo "Penjualan berhasil dicatat!";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 20px;
        text-align: center;
    }

    form {
        background: white;
        padding: 20px;
        width: 300px;
        margin: auto;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        font-weight: bold;
        margin-top: 10px;
        text-align: left;
    }

    select, input {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    button {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        margin-top: 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #0056b3;
    }

    a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        text-decoration: none;
        background: #007bff;
        color: white;
        border-radius: 5px;
        transition: 0.3s;
    }

    a:hover {
        background: #0056b3;
    }
</style>

<form method="post">
    <label>Nama Karyawan:</label>
    <select name="id_karyawan" required>
        <option value="">-- Pilih Karyawan --</option>
        <?php while ($row = $result_karyawan->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_karyawan']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Jumlah Terjual:</label>
    <input type="number" name="jumlah_terjual" required>

    <button type="submit">Input Penjualan</button>
</form>

<a href="index.php">Kembali</a>
