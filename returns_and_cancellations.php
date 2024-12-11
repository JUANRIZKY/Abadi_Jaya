<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pesanan
$query_orders = "SELECT * FROM orders WHERE user_id = $user_id";
$result_orders = mysqli_query($conn, $query_orders);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengembalian & Pembatalan - Abadi Jaya</title>
</head>
<body>
    <h2>Pengajuan Pengembalian/Batal Pesanan</h2>
    <table border="1">
        <tr>
            <th>ID Pesanan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($order = mysqli_fetch_assoc($result_orders)) { ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td>
                    <!-- Form Pengembalian -->
                    <form method="POST" action="return_process.php">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <textarea name="reason" placeholder="Alasan pengembalian" required></textarea>
                        <button type="submit">Ajukan Pengembalian</button>
                    </form>

                    <!-- Form Pembatalan -->
                    <form method="POST" action="cancel_process.php">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <textarea name="reason" placeholder="Alasan pembatalan" required></textarea>
                        <button type="submit">Ajukan Pembatalan</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
