<?php
$conn = new mysqli("localhost", "root", "", "gentamandiri");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jumlah_masuk = (int) $_POST['jumlah_masuk'];
    $tanggal_input = date("Y-m-d H:i:s"); // Waktu input

    // Ambil ID terakhir dari stokbarang
    $query = "SELECT id, jumlah_stok FROM stokbarang ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Jika stok ada, update stok terbaru
        $row = $result->fetch_assoc();
        $stok_baru = $row['jumlah_stok'] + $jumlah_masuk;
        $id_terakhir = $row['id'];

        $sql = "UPDATE stokbarang SET jumlah_stok = $stok_baru, tanggal_input = '$tanggal_input' WHERE id = $id_terakhir";
    } else {
        // Jika stok belum ada, masukkan stok baru
        $sql = "INSERT INTO stokbarang (jumlah_stok, tanggal_input) VALUES ($jumlah_masuk, '$tanggal_input')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "✅ Stok berhasil diperbarui!";
    } else {
        echo "❌ Error: " . $conn->error;
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

    input {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    button {
        background: #28a745;
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
        background: #218838;
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
    <label>Jumlah Stok Masuk:</label>
    <input type="number" name="jumlah_masuk" required>
    <button type="submit">Tambah Stok</button>
</form>
<a href="index.php">Kembali</a>
