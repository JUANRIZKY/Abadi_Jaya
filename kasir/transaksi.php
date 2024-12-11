<?php
// Koneksi ke database
include '../includes/db.php';

// Cek jika kasir sudah login
session_start();
if ($_SESSION['role'] != 'kasir') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Penjualan - Kasir</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Transaksi Penjualan</h1>
    <a href="index.php">Kembali ke Beranda</a>

    <form method="POST" action="proses_transaksi.php">
        <h2>Pilih Produk</h2>
        
        <?php
        // Ambil data produk dari database
        $query = "SELECT * FROM products WHERE stock > 0";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($product = mysqli_fetch_assoc($result)) {
                echo "<div>";
                echo "<input type='checkbox' name='products[{$product['id']}]' value='{$product['id']}' /> ";
                echo "<strong>{$product['name']}</strong> - Rp. {$product['price']} <br>";
                echo "Stok: {$product['stock']} <br>";
                echo "<input type='number' name='quantity[{$product['id']}]' min='1' max='{$product['stock']}' placeholder='Jumlah'>";
                echo "</div><hr>";
            }
        } else {
            echo "<p>Produk tidak tersedia.</p>";
        }
        ?>

        <br><br>
        <button type="submit" name="submit">Proses Transaksi</button>
    </form>
</body>
</html>
