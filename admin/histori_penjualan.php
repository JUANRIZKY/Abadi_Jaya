<?php
session_start();

// Pastikan admin sudah login
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Koneksi ke database
include 'includes/db.php'; // Sesuaikan dengan file koneksi Anda

// Ambil data histori penjualan
$query = "SELECT * FROM histori_penjualan ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Histori Penjualan - Abadi Jaya</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Histori Penjualan</h1>
    <table>
        <tr>
            <th>ID Transaksi</th>
            <th>User</th>
            <th>Total</th>
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td> <!-- Sesuaikan jika ada tabel users -->
                <td><?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                <td><?php echo $row['tanggal']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
