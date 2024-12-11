<?php
session_start();

// Fungsi untuk menambahkan produk ke keranjang
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $product_id = $_GET['product_id'];
    $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1;

    // Jika keranjang belum ada, buat array baru
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Jika produk sudah ada di keranjang, tambah jumlahnya
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Jika produk belum ada, tambahkan produk baru ke keranjang
        $_SESSION['cart'][$product_id] = array(
            'quantity' => $quantity
        );
    }
}

// Fungsi untuk menghapus produk dari keranjang
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = $_GET['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

// Fungsi untuk memperbarui jumlah produk di keranjang
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Ambil produk dari database
// Ganti kode ini dengan query untuk mengambil produk dari database
$products = [
    1 => ['name' => 'Produk 1', 'price' => 10000],
    2 => ['name' => 'Produk 2', 'price' => 20000],
    3 => ['name' => 'Produk 3', 'price' => 15000],
];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja - Abadi Jaya</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Keranjang Belanja</h1>
    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="keranjang.php">
            <table>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $product = $products[$product_id];
                    $item_total = $product['price'] * $item['quantity'];
                    $total += $item_total;
                ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                    <td>
                        <input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                    </td>
                    <td><?php echo number_format($item_total, 0, ',', '.'); ?></td>
                    <td><a href="keranjang.php?action=remove&product_id=<?php echo $product_id; ?>">Hapus</a></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td colspan="2"><?php echo number_format($total, 0, ',', '.'); ?></td>
                </tr>
            </table>
            <button type="submit" name="update">Perbarui Keranjang</button>
        </form>
        <a href="pembayaran.php">Lanjutkan ke Pembayaran</a>
    <?php else: ?>
        <p>Keranjang belanja Anda kosong.</p>
        <a href="index.php">Kembali ke Beranda</a>
    <?php endif; ?>
</body>
</html>
