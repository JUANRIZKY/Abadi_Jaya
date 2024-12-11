<?php
// Koneksi ke database
include 'includes/db.php';

// Cek jika admin sudah login
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Daftar Pesanan</h1>
    <a href="index.php">Kembali ke Beranda</a>

    <?php
    // Ambil data pesanan dari database
    $query = "SELECT * FROM orders ORDER BY order_date DESC";
    $result = mysqli_query($conn, $query);

    // Cek apakah ada pesanan
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
        echo "<thead><tr><th>ID Pesanan</th><th>Nama Pembeli</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead><tbody>";
        
        // Menampilkan data pesanan
        while ($order = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $order['id'] . "</td>";
            echo "<td>" . $order['customer_name'] . "</td>";
            echo "<td>" . $order['order_date'] . "</td>";
            echo "<td>" . $order['status'] . "</td>";
            echo "<td>";
            echo "<a href='update_status.php?id=" . $order['id'] . "'>Update Status</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Belum ada pesanan.</p>";
    }
    ?>
</body>
</html>
