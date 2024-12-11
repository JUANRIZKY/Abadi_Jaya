<?php
include 'includes/db.php';

// Query untuk menampilkan produk
$sql = "SELECT * FROM produk";
$result = $conn->query($sql);

echo "<table border='1'>
<tr>
    <th>Nama Produk</th>
    <th>Stok</th>
    <th>Harga</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . $row['nama'] . "</td>
            <td>" . $row['stok'] . "</td>
            <td>Rp" . number_format($row['harga'], 2, ',', '.') . "</td>
          </tr>";
}

echo "</table>";

$conn->close();
?>
