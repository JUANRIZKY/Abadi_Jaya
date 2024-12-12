<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Periksa username dan ambil data user
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['role'] = $user['role'];  // Role: admin, kasir, user
            $_SESSION['username'] = $user['username'];

            // Redirect berdasarkan role
            if ($user['role'] == 'admin') {
                header('Location: admin/index.php');
            } elseif ($user['role'] == 'kasir') {
                header('Location: kasir/index.php');
            } else {
                header('Location: user/index.php');
            }
            exit();
        } else {
            $error = "Login gagal. Password salah.";
        }
    } else {
        $error = "Login gagal. Username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Abadi Jaya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        form h2 {
            margin-bottom: 20px;
            color: #444;
        }
        form input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #45a049;
        }
        form .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        a.link {
            display: block;
            margin-top: 15px;
            color: #2196F3;
            text-decoration: none;
            font-size: 14px;
        }
        a.link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <!-- Tautan Registrasi -->
        <a href="register.php" class="link">Belum punya akun? Daftar di sini</a>
    </form>
</body>
</html>
