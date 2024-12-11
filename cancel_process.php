<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO cancellations (order_id, user_id, reason) VALUES ('$order_id', '$user_id', '$reason')";
    if (mysqli_query($conn, $query)) {
        echo "Permohonan pembatalan berhasil diajukan.";
        // Kirim notifikasi
        // mail($user_email, "Pengajuan Pembatalan", "Permohonan Anda sedang diproses.", "From: admin@abadi-jaya.com");
        header("Location: returns_and_cancellations.php");
    } else {
        echo "Gagal mengajukan pembatalan: " . mysqli_error($conn);
    }
}
?>
