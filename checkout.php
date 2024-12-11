<?php
session_start();
include 'includes/db.php';

// Inisialisasi variabel
$total_price = 0;
$discount_percentage = 0;
$flash_sale_price = 0;

// Query keranjang belanja pengguna
$user_id = $_SESSION['user_id'];
$query = "SELECT p.name, p.price, p.discount_percentage, c.quantity 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = '$user_id'";
$result = mysqli_query($conn, $query);

// Hitung total harga
while ($row = mysqli_fetch_assoc($result)) {
    $original_price = $row['price'] * $row['quantity'];
    $discounted_price = $original_price - ($original_price * ($row['discount_percentage'] / 100));
    $total_price += $discounted_price;
}

// Proses Kode Promo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promo_code'])) {
    $promo_code = $_POST['promo_code'];

    // Query untuk mengecek kode promo
    $promo_query = "SELECT * FROM promotions WHERE promo_code='$promo_code' AND active=1 AND start_date <= CURDATE() AND end_date >= CURDATE()";
    $promo_result = mysqli_query($conn, $promo_query);

    if (mysqli_num_rows($promo_result) > 0) {
        $promo = mysqli_fetch_assoc($promo_result);
        $discount_percentage = $promo['discount_percentage'];
        $category = $promo['category'];

        // Hitung diskon tambahan dari kode promo
        $promo_discount = ($total_price * ($discount_percentage / 100));
        $total_price -= $promo_discount;

        $promo_message = "Kode promo berhasil diterapkan. Anda mendapatkan diskon tambahan $discount_percentage%.";
    } else {
        $promo_message = "Kode promo tidak valid atau sudah kadaluarsa.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Abadi Jaya</title>
</head>
<body>
    <h2>Checkout</h2>
    <div>
        <h3>Detail Keranjang Belanja</h3>
        <?php
        mysqli_data_seek($result, 0); // Reset hasil query untuk ditampilkan
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <p>
                Produk: <?php echo $row['name']; ?><br>
                Harga Satuan: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?><br>
                Jumlah: <?php echo $row['quantity']; ?><br>
                Subtotal: Rp <?php echo number_format($row['price'] * $row['quantity'], 0, ',', '.'); ?><br>
                Diskon Produk: <?php echo $row['discount_percentage']; ?>%<br>
                Total Setelah Diskon: Rp <?php echo number_format($row['price'] * $row['quantity'] * (1 - $row['discount_percentage'] / 100), 0, ',', '.'); ?>
            </p>
            <hr>
        <?php } ?>
    </div>

    <div>
        <h3>Form Kode Promo</h3>
        <form method="POST">
            <label for="promo_code">Masukkan Kode Promo:</label>
            <input type="text" id="promo_code" name="promo_code" placeholder="Kode Promo">
            <button type="submit">Gunakan Kode Promo</button>
        </form>
        <?php if (isset($promo_message)) echo "<p>$promo_message</p>"; ?>
    </div>

    <div>
        <h3>Total Pembayaran</h3>
        <p>Total Sebelum Promo: Rp <?php echo number_format($total_price / (1 - $discount_percentage / 100), 0, ',', '.'); ?></p>
        <p>Diskon Promo: Rp <?php echo number_format(($total_price / (1 - $discount_percentage / 100)) - $total_price, 0, ',', '.'); ?></p>
        <p><strong>Total Setelah Promo: Rp <?php echo number_format($total_price, 0, ',', '.'); ?></strong></p>
    </div>

    <div>
        <h3>Pilih Metode Pembayaran</h3>
        <form method="POST" action="proses_pembayaran.php">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
            <label for="payment_method">Metode Pembayaran:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="bank_transfer">Transfer Bank</option>
                <option value="ewallet">E-Wallet (GoPay, OVO, Dana)</option>
                <option value="qris">QRIS</option>
                <option value="cod">Cash on Delivery (COD)</option>
            </select>
            <button type="submit">Bayar Sekarang</button>
        </form>
    </div>
</body>
</html>
