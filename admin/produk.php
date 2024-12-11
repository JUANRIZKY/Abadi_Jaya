<?php
session_start();
include 'includes/db.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Tambah Produk Baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama_produk', '$harga', '$stok')";
    mysqli_query($conn, $query);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk - Admin</title>
</head>
<body>
    <h2>Kelola Produk</h2>
    <form method="POST">
        <input type="text" name="nama_produk" placeholder="Nama Produk" required><br>
        <input type="number" name="harga" placeholder="Harga" required><br>
        <input type="number" name="stok" placeholder="Stok" required><br>
        <button type="submit">Tambah Produk</button>
    </form>
    <h3>Daftar Produk</h3>
    <ul>
        <?php
        $query = "SELECT * FROM produk";
        $result = mysqli_query($conn, $query);
        while ($produk = mysqli_fetch_assoc($result)) {
            echo "<li>" . $produk['nama_produk'] . " - Rp" . $produk['harga'] . " - Stok: " . $produk['stok'] . "</li>";
        }
        ?>
    </ul>
</body>
</html>
