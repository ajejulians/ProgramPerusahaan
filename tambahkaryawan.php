<?php
$conn = new mysqli("localhost", "root", "", "gentamandiri");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_karyawan = trim($_POST['nama_karyawan']);

    if (!empty($nama_karyawan)) {
        $sql = "INSERT INTO karyawan (nama_karyawan) VALUES ('$nama_karyawan')";
        if ($conn->query($sql) === TRUE) {
            echo "Karyawan berhasil ditambahkan!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Nama karyawan tidak boleh kosong!";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        text-align: center;
        margin: 20px;
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
    <label>Nama Karyawan:</label>
    <input type="text" name="nama_karyawan" required>
    <button type="submit">Tambah Karyawan</button>
</form>
<a href="index.php">Kembali</a>
