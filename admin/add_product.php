<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // Query untuk menambah produk
    $sql = "INSERT INTO produk (nama, stok, harga) VALUES ('$nama', $stok, $harga)";

    if ($conn->query($sql) === TRUE) {
        echo "Produk baru berhasil ditambahkan.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="POST">
    Nama Produk: <input type="text" name="nama" required><br>
    Stok: <input type="number" name="stok" required><br>
    Harga: <input type="text" name="harga" required><br>
    <input type="submit" value="Tambah Produk">
</form>
