<?php
// Konfigurasi API
$base_url = "https://api.accurate.id/accurate/api/v1/"; // Ganti URL jika menggunakan Jurnal.id
$api_key = "YOUR_API_KEY"; // Ganti dengan API Key Anda

// Koneksi Database
include 'includes/db.php';

// Fungsi untuk mengirim data ke API
function kirimDataKeAPI($endpoint, $data, $api_key) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200 || $http_code === 201) {
        return json_decode($response, true);
    } else {
        return false;
    }
}

// Data Penjualan
$query_sales = "SELECT * FROM orders WHERE status = 'completed'"; // Contoh tabel orders
$result_sales = mysqli_query($conn, $query_sales);

while ($order = mysqli_fetch_assoc($result_sales)) {
    $order_id = $order['id'];
    $items = [];
    
    $query_items = "SELECT * FROM order_items WHERE order_id = $order_id"; // Contoh tabel order_items
    $result_items = mysqli_query($conn, $query_items);
    
    while ($item = mysqli_fetch_assoc($result_items)) {
        $items[] = [
            "product_name" => $item['product_name'],
            "quantity" => $item['quantity'],
            "rate" => $item['price']
        ];
    }

    // Kirim Data Penjualan
    $data_penjualan = [
        "sales_invoice" => [
            "transaction_date" => $order['order_date'],
            "due_date" => date("Y-m-d", strtotime($order['order_date'] . " +7 days")),
            "items" => $items,
            "customer_name" => $order['customer_name'],
            "memo" => "Penjualan toko Abadi Jaya"
        ]
    ];

    $response_penjualan = kirimDataKeAPI($base_url . "sales_invoices", $data_penjualan, $api_key);
    if ($response_penjualan) {
        echo "Penjualan Order ID $order_id berhasil dicatat.<br>";
    } else {
        echo "Gagal mencatat penjualan Order ID $order_id.<br>";
    }
}

// Pembaruan Stok
$query_products = "SELECT * FROM products";
$result_products = mysqli_query($conn, $query_products);

while ($product = mysqli_fetch_assoc($result_products)) {
    $data_stok = [
        "item_no" => $product['id'],
        "name" => $product['name'],
        "quantity" => $product['stock']
    ];

    $response_stok = kirimDataKeAPI($base_url . "inventory_adjustments", $data_stok, $api_key);
    if ($response_stok) {
        echo "Stok produk " . $product['name'] . " berhasil diperbarui.<br>";
    } else {
        echo "Gagal memperbarui stok produk " . $product['name'] . ".<br>";
    }
}
?>
