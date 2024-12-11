<?php
$host = 'localhost';
$username = 'root';
$password = 'argajuan123';
$database = 'abadi_jaya';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
