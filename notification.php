<?php
require_once 'includes/db.php'; // Koneksi ke database

// Memastikan hanya request POST yang diproses
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari request
    $order_id = $_POST['order_id'] ?? null;
    $transaction_status = $_POST['transaction_status'] ?? null;

    // Validasi data
    if (!$order_id || !$transaction_status) {
        echo "Error: Data tidak lengkap.";
        http_response_code(400); // Bad Request
        exit;
    }

    // Logging untuk debug (opsional)
    error_log("Order ID: $order_id | Status: $transaction_status");

    // Update status pembayaran di database
    $query = "UPDATE orders SET payment_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $transaction_status, $order_id);

    if ($stmt->execute()) {
        echo "Status pembayaran untuk Order ID: $order_id berhasil diperbarui menjadi $transaction_status.";
    } else {
        error_log("Gagal memperbarui status pembayaran untuk Order ID: $order_id");
        echo "Terjadi kesalahan saat memperbarui status pembayaran.";
    }

    // Response HTTP 200 OK
    http_response_code(200);
} else {
    // Jika bukan request POST
    echo "Metode request tidak valid.";
    http_response_code(405); // Method Not Allowed
}
