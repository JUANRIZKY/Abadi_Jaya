<?php
// Cek apakah sesi sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user'; // Default role untuk pengguna baru

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah digunakan
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username sudah terdaftar. Silakan gunakan username lain.";
        } else {
            // Hash password dan simpan ke database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";

            if (mysqli_query($conn, $insert_query)) {
                session_start();
                $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
                header('Location: login.php');
                exit();
            } else {
                $error = "Terjadi kesalahan saat mendaftarkan pengguna.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Abadi Jaya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 95%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .link {
            margin-top: 10px;
            display: block;
            font-size: 14px;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Register</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required><br>
        <button type="submit">Daftar</button>
        <!-- Tautan ke halaman login -->
        <a href="login.php" class="link">Sudah punya akun? Login di sini</a>
    </form>
</body>
</html>
