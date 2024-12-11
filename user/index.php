<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Abadi Jaya</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Selamat Datang di Abadi Jaya</h1>
    
    <!-- Form Pencarian Produk -->
    <form method="GET" action="index.php">
        <input type="text" name="search" placeholder="Cari produk..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Cari</button>
    </form>

    <!-- Menu Link -->
    <a href="produk.php">Lihat Produk</a><br>
    <a href="keranjang.php">Keranjang Belanja</a>

    <!-- Daftar Produk Berdasarkan Pencarian -->
    <?php
    // Koneksi ke database
    include '../includes/db.php';

    // Ambil kata kunci pencarian dari form
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Query untuk mencari produk
    $query = "SELECT * FROM products WHERE name LIKE '%$search%'";
    $result = mysqli_query($conn, $query);

    // Menampilkan produk yang sesuai dengan pencarian
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Hasil Pencarian:</h2>";
        while ($product = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            echo "<img src='../images/" . $product['image'] . "' alt='" . $product['name'] . "' width='200'>";
            echo "<h3>" . $product['name'] . "</h3>";
            echo "<p>Harga: Rp " . number_format($product['price'], 0, ',', '.') . "</p>";
            echo "<a href='keranjang.php?id=" . $product['id'] . "'>Tambahkan ke Keranjang</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Produk tidak ditemukan.</p>";
    }
    ?>
</body>
</html>
