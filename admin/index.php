<?php
session_start();
include 'includes/db.php';

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Dashboard Admin - Abadi Jaya</h1>
    <a href="produk.php">Kelola Produk</a><br>
    <a href="pesanan.php">Kelola Pesanan</a>
    <h1>Selamat datang di Sistem Pengelolaan Penjualan dan Stok</h1>
<ul>
    <li><a href="add_product.php">Tambah Produk</a></li>
    <li><a href="process_sale.php">Proses Penjualan</a></li>
    <li><a href="display_products.php">Tampilkan Produk</a></li>
</ul>
</body>
</html>
