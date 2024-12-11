<?php
include 'includes/db.php';

$user_id = $_POST['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$total = $_POST['total'];

// Kurangi stok produk
$conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");

// Tambahkan data penjualan
$sql = "INSERT INTO sales (user_id, product_id, quantity, total) VALUES ('$user_id', '$product_id', '$quantity', '$total')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
