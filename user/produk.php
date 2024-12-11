<?php
session_start();
include 'includes/db.php'; // Menyertakan koneksi ke database

// Ambil semua data produk dari database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Produk - Abadi Jaya</title>
</head>
<body>
    <h2>Katalog Produk</h2>
    <div class="products">
        <?php while ($product = mysqli_fetch_assoc($result)) { ?>
            <div class="product">
                <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="200">
                <h3><?php echo $product['name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
                <p>Harga: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                <p>Stok: <?php echo $product['stock']; ?> unit</p>
                <a href="add_to_cart.php?id=<?php echo $product['id']; ?>">Tambahkan ke Keranjang</a>
            </div>
        <?php } ?>
    </div>
</body>
</html>
