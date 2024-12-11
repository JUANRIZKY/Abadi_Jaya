<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produk_id = $_POST['produk_id'];
    $jumlah = $_POST['jumlah'];

    // Query untuk mendapatkan informasi produk
    $sql = "SELECT * FROM produk WHERE id = $produk_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stok_tersedia = $row['stok'];
        $harga_produk = $row['harga'];

        if ($stok_tersedia >= $jumlah) {
            // Mengurangi stok
            $new_stok = $stok_tersedia - $jumlah;
            $update_stok = "UPDATE produk SET stok = $new_stok WHERE id = $produk_id";
            $conn->query($update_stok);

            // Menambahkan transaksi penjualan
            $total_harga = $jumlah * $harga_produk;
            $insert_penjualan = "INSERT INTO penjualan (produk_id, jumlah, total_harga) VALUES ($produk_id, $jumlah, $total_harga)";
            $conn->query($insert_penjualan);

            echo "Penjualan berhasil! Total harga: Rp" . number_format($total_harga, 2, ',', '.');
        } else {
            echo "Stok tidak mencukupi!";
        }
    } else {
        echo "Produk tidak ditemukan.";
    }
}
?>

<form method="POST">
    Pilih Produk:
    <select name="produk_id" required>
        <?php
        $sql = "SELECT * FROM produk";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['nama'] . " - Rp" . number_format($row['harga'], 2, ',', '.') . "</option>";
        }
        ?>
    </select><br>
    Jumlah: <input type="number" name="jumlah" required><br>
    <input type="submit" value="Proses Penjualan">
</form>
