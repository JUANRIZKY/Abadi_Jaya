<?php
// Koneksi ke database
include '../includes/db.php';

// Cek jika kasir sudah login
session_start();
if ($_SESSION['role'] != 'kasir') {
    header('Location: ../login.php');
    exit();
}

// Proses transaksi jika tombol submit ditekan
if (isset($_POST['submit'])) {
    $products = $_POST['products'];  // Produk yang dipilih
    $quantity = $_POST['quantity'];  // Jumlah yang dibeli
    $total_amount = 0;
    $items = [];  // Array untuk menyimpan detail produk yang dibeli

    foreach ($products as $product_id) {
        $query = "SELECT * FROM products WHERE id = '$product_id'";
        $result = mysqli_query($conn, $query);
        $product = mysqli_fetch_assoc($result);

        // Menghitung total harga produk yang dibeli
        $price = $product['price'];
        $quantity_bought = $quantity[$product_id];
        $total_amount += $price * $quantity_bought;

        // Menambahkan detail produk ke dalam transaksi
        $items[] = [
            'product_id' => $product_id,
            'quantity' => $quantity_bought,
            'total' => $price * $quantity_bought
        ];

        // Update stok produk
        $new_stock = $product['stock'] - $quantity_bought;
        $update_query = "UPDATE products SET stock = '$new_stock' WHERE id = '$product_id'";
        mysqli_query($conn, $update_query);
    }

    // Simpan transaksi ke database (tabel transaksi dan detail transaksi)
    $kasir_id = $_SESSION['user_id']; // ID Kasir (dari session)
    $query = "INSERT INTO transactions (kasir_id, total_amount) VALUES ('$kasir_id', '$total_amount')";
    if (mysqli_query($conn, $query)) {
        $transaction_id = mysqli_insert_id($conn);  // ID transaksi yang baru saja disimpan

        // Simpan detail transaksi
        foreach ($items as $item) {
            $query_detail = "INSERT INTO transaction_details (transaction_id, product_id, quantity, total) 
                            VALUES ('$transaction_id', '{$item['product_id']}', '{$item['quantity']}', '{$item['total']}')";
            mysqli_query($conn, $query_detail);
        }

        // Redirect ke halaman struk
        header("Location: cetak_struk.php?id=$transaction_id");
        exit(); // Pastikan proses berhenti setelah redirect
    } else {
        echo "<p>Terjadi kesalahan dalam proses transaksi.</p>";
    }
}
?>
