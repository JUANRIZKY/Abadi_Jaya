<?php
// Mulai session
session_start();

// Redirect berdasarkan role (jika dibutuhkan)
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin/index.php');
        exit();
    } elseif ($_SESSION['role'] == 'kasir') {
        header('Location: kasir/index.php');
        exit();
    } elseif ($_SESSION['role'] == 'user') {
        header('Location: user/index.php');
        exit();
    }
}

// Koneksi ke database
include 'includes/db.php'; // Pastikan file ini benar

// Validasi input untuk pencarian dan kategori
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Menggunakan prepared statement untuk keamanan
$query = "SELECT * FROM products WHERE name LIKE ? AND (category LIKE ? OR ? = '')";
$stmt = mysqli_prepare($conn, $query);
$search_param = "%$search%";
$category_param = "%$category%";
mysqli_stmt_bind_param($stmt, "sss", $search_param, $category_param, $category);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Cek error pada query
if (!$result) {
    die("Error pada query: " . mysqli_error($conn));
}

// Tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Cek stok produk
    $stock_query = "SELECT stock FROM products WHERE id = ?";
    $stock_stmt = mysqli_prepare($conn, $stock_query);
    mysqli_stmt_bind_param($stock_stmt, "i", $product_id);
    mysqli_stmt_execute($stock_stmt);
    $stock_result = mysqli_stmt_get_result($stock_stmt);
    $stock_row = mysqli_fetch_assoc($stock_result);

    if ($stock_row['stock'] >= $quantity) {
        // Update stok produk
        $update_query = "UPDATE products SET stock = stock - ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $quantity, $product_id);
        mysqli_stmt_execute($update_stmt);

        echo "<script>alert('Produk berhasil ditambahkan ke keranjang!');</script>";
    } else {
        echo "<script>alert('Stok produk tidak mencukupi!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Produk</title>
    <style>
        .product {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>Katalog Produk</h1>

    <!-- Form Pencarian -->
    <form method="GET" action="index.php">
        <input type="text" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="category">
            <option value="">Pilih Kategori</option>
            <option value="Elektronik" <?php echo ($category == 'Elektronik') ? 'selected' : ''; ?>>Elektronik</option>
            <option value="Perlengkapan Sekolah" <?php echo ($category == 'Perlengkapan Sekolah') ? 'selected' : ''; ?>>Perlengkapan Sekolah</option>
            <option value="Rumah Tangga" <?php echo ($category == 'Rumah Tangga') ? 'selected' : ''; ?>>Rumah Tangga</option>
        </select>
        <button type="submit">Cari</button>
    </form>

    <!-- Daftar Produk -->
    <div class="products">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($product = mysqli_fetch_assoc($result)) { ?>
                <div class="product">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Harga: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                    <p>Stok: <?php echo $product['stock']; ?></p>
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="quantity" placeholder="Jumlah" min="1" max="<?php echo $product['stock']; ?>" required>
                        <button type="submit" name="add_to_cart">Tambah ke Keranjang</button>
                    </form>
                </div>
            <?php }
        } else {
            echo "<p>Tidak ada produk ditemukan.</p>";
        }
        ?>
    </div>
</body>
</html>
