<?php
session_start();
include '../includes/db.php'; // Pastikan path file database sudah sesuai

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Ambil user ID dari session
    $total = $_POST['total']; // Total harga yang diambil dari keranjang
    $payment_method = $_POST['payment_method']; // Metode pembayaran
    $status = 'pending'; // Default status pembayaran
    
    // Kode unik untuk memudahkan verifikasi
    $kode_unik = rand(1, 999);
    $total_pembayaran = $total + $kode_unik;

    // Simpan data pembayaran ke database
    $query = "INSERT INTO pembayaran (user_id, total, kode_unik, metode, status) VALUES ('$user_id', '$total', '$kode_unik', '$payment_method', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if ($payment_method == 'transfer') {
            // Menampilkan informasi pembayaran Transfer Bank
            echo "<h3>Silakan transfer pembayaran Anda ke rekening berikut:</h3>";
            echo "Bank: BCA<br>";
            echo "Nomor Rekening: 1234567890<br>";
            echo "Atas Nama: Abadi Jaya<br>";
            echo "Total yang harus dibayar: <strong>Rp " . number_format($total_pembayaran, 0, ',', '.') . "</strong><br>";
            echo "<p>Setelah transfer, unggah bukti pembayaran di halaman konfirmasi pembayaran.</p>";
        } elseif ($payment_method == 'cod') {
            // Menampilkan pesan sukses untuk COD
            echo "<h3>Pesanan Anda berhasil dibuat!</h3>";
            echo "<p>Pesanan akan dikirimkan ke alamat Anda, dan pembayaran dilakukan di tempat (COD).</p>";
        } elseif ($payment_method == 'qris') {
            // Menampilkan QR Code untuk pembayaran QRIS
            echo "<h3>Silakan lakukan pembayaran menggunakan QRIS:</h3>";
            echo "<img src='../images/qris.png' alt='QRIS Code' style='width:300px; height:300px;'><br>";
            echo "Total yang harus dibayar: <strong>Rp " . number_format($total_pembayaran, 0, ',', '.') . "</strong><br>";
            echo "<p>Pindai QR Code di atas menggunakan aplikasi pembayaran digital Anda (Dana, OVO, GoPay, dll).</p>";
        } elseif ($payment_method == 'ewallet') {
            // Menampilkan informasi pembayaran e-Wallet
            echo "<h3>Pilih aplikasi e-Wallet Anda:</h3>";
            echo "1. Dana<br>";
            echo "2. OVO<br>";
            echo "3. GoPay<br>";
            echo "<p>Nomor tujuan transfer: <strong>0812-3456-7890</strong></p>";
            echo "Total yang harus dibayar: <strong>Rp " . number_format($total_pembayaran, 0, ',', '.') . "</strong><br>";
            echo "<p>Setelah pembayaran, unggah bukti pembayaran di halaman konfirmasi pembayaran.</p>";
        }
    } else {
        echo "<p>Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
</head>
<body>
    <h2>Pilih Metode Pembayaran</h2>
    <form method="POST">
        <input type="hidden" name="total" value="150000"> <!-- Ganti total ini dengan nilai sebenarnya -->
        <label>
            <input type="radio" name="payment_method" value="transfer" required> Transfer Bank
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="cod" required> Cash on Delivery (COD)
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="qris" required> QRIS
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="ewallet" required> e-Wallet (Dana, OVO, GoPay)
        </label><br><br>
        <button type="submit">Bayar Sekarang</button>
    </form>
</body>
</html>
