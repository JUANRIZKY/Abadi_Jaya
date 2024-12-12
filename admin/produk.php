<?php
session_start();
include 'includes/db.php';

// Periksa jika user bukan admin
if ($_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Tambah Produk Baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_produk'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, stok) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param("sii", $nama_produk, $harga, $stok);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Produk berhasil ditambahkan.</p>";
    } else {
        echo "<p style='color: red;'>Gagal menambahkan produk: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Hapus Produk
if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Produk berhasil dihapus.</p>";
    } else {
        echo "<p style='color: red;'>Gagal menghapus produk.</p>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Kelola Produk</h2>

    <!-- Form Tambah Produk -->
    <form method="POST">
        <input type="text" name="nama_produk" placeholder="Nama Produk" required><br>
        <input type="number" name="harga" placeholder="Harga" required min="1"><br>
        <input type="number" name="stok" placeholder="Stok" required min="0"><br>
        <button type="submit" name="tambah_produk">Tambah Produk</button>
    </form>

    <h3>Daftar Produk</h3>
    <ul>
        <?php
        // Query untuk mendapatkan semua produk
        $query = "SELECT * FROM produk";
        $result = mysqli_query($conn, $query);
        while ($produk = mysqli_fetch_assoc($result)) {
            echo "<li>" . htmlspecialchars($produk['nama_produk']) . " - Rp" . number_format($produk['harga'], 0, ',', '.') . " - Stok: " . $produk['stok'];
            echo " <a href='kelola_produk.php?hapus_id=" . $produk['id'] . "' onclick='return confirm(\"Yakin ingin menghapus produk ini?\");'>Hapus</a></li>";
        }
        ?>
    </ul>
</body>
</html>
