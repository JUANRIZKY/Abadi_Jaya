<?php
// Koneksi ke database
include 'includes/db.php';

// Cek jika admin sudah login
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Ambil ID pesanan dari URL
$order_id = $_GET['id'];

// Proses update status pesanan
if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $query = "UPDATE orders SET status='$status' WHERE id='$order_id'";
    if (mysqli_query($conn, $query)) {
        header('Location: pesanan.php'); // Kembali ke halaman pesanan setelah update
        exit();
    } else {
        echo "Gagal memperbarui status pesanan.";
    }
}

// Ambil data pesanan berdasarkan ID
$query = "SELECT * FROM orders WHERE id='$order_id'";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Status Pesanan</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Update Status Pesanan</h1>
    <form method="POST">
        <label for="status">Status Pesanan:</label>
        <select name="status" id="status">
            <option value="pending" <?php if ($order['status'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="proses" <?php if ($order['status'] == 'proses') echo 'selected'; ?>>Proses</option>
            <option value="dikirim" <?php if ($order['status'] == 'dikirim') echo 'selected'; ?>>Dikirim</option>
            <option value="selesai" <?php if ($order['status'] == 'selesai') echo 'selected'; ?>>Selesai</option>
        </select>
        <br>
        <button type="submit" name="update">Update Status</button>
    </form>

    <a href="pesanan.php">Kembali ke Daftar Pesanan</a>
</body>
</html>
