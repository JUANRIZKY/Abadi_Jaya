<?php
session_start();
include '../includes/db.php';

// Pastikan hanya kasir yang bisa mengakses
if ($_SESSION['role'] != 'kasir') {
    header('Location: ../user/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Dashboard Kasir - Abadi Jaya</h1>
    <a href="transaksi.php">Pencatatan Transaksi</a><br>
    <a href="histori.php">Histori Penjualan</a>
</body>
</html>
