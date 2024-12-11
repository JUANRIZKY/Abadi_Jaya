<?php
include 'includes/db.php';

$product_id = $_POST['product_id'];
$new_stock = $_POST['new_stock'];

$sql = "UPDATE products SET stock = $new_stock WHERE id = $product_id";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
