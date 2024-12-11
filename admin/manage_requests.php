<?php
session_start();
include 'includes/db.php';

// Ambil data pengajuan pengembalian dan pembatalan
$query_returns = "SELECT r.*, o.status AS order_status, u.username 
                  FROM returns r 
                  JOIN orders o ON r.order_id = o.id 
                  JOIN users u ON r.user_id = u.id";
$query_cancellations = "SELECT c.*, o.status AS order_status, u.username 
                        FROM cancellations c 
                        JOIN orders o ON c.order_id = o.id 
                        JOIN users u ON c.user_id = u.id";

$result_returns = mysqli_query($conn, $query_returns);
$result_cancellations = mysqli_query($conn, $query_cancellations);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Permohonan - Admin</title>
</head>
<body>
    <h2>Pengajuan Pengembalian</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>ID Pesanan</th>
            <th>Username</th>
            <th>Alasan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($return = mysqli_fetch_assoc($result_returns)) { ?>
            <tr>
                <td><?php echo $return['id']; ?></td>
                <td><?php echo $return['order_id']; ?></td>
                <td><?php echo $return['username']; ?></td>
                <td><?php echo $return['reason']; ?></td>
                <td><?php echo $return['status']; ?></td>
                <td>
                    <a href="process_request.php?type=return&id=<?php echo $return['id']; ?>&action=approve">Setujui</a> |
                    <a href="process_request.php?type=return&id=<?php echo $return['id']; ?>&action=reject">Tolak</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Pengajuan Pembatalan</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>ID Pesanan</th>
            <th>Username</th>
            <th>Alasan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($cancel = mysqli_fetch_assoc($result_cancellations)) { ?>
            <tr>
                <td><?php echo $cancel['id']; ?></td>
                <td><?php echo $cancel['order_id']; ?></td>
                <td><?php echo $cancel['username']; ?></td>
                <td><?php echo $cancel['reason']; ?></td>
                <td><?php echo $cancel['status']; ?></td>
                <td>
                    <a href="process_request.php?type=cancel&id=<?php echo $cancel['id']; ?>&action=approve">Setujui</a> |
                    <a href="process_request.php?type=cancel&id=<?php echo $cancel['id']; ?>&action=reject">Tolak</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>