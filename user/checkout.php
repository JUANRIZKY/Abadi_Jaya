<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h2>Checkout</h2>
    <form method="POST" action="proses_checkout.php">
        <input type="text" name="nama" placeholder="Nama Lengkap" required><br>
        <input type="text" name="alamat" placeholder="Alamat Pengiriman" required><br>
        <input type="text" name="telepon" placeholder="Nomor Telepon" required><br>
        <button type="submit">Bayar Sekarang</button>
    </form>
</body>
</html>
