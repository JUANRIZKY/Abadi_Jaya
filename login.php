<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['role'] = $user['role'];  // Role: admin, kasir, user
        $_SESSION['username'] = $user['username'];

        if ($user['role'] == 'admin') {
            header('Location: admin/index.php');
        } elseif ($user['role'] == 'kasir') {
            header('Location: kasir/index.php');
        } else {
            header('Location: user/index.php');
        }
    } else {
        echo "Login gagal. Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Abadi Jaya</title>
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

