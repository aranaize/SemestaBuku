<?php
session_start();
require_once 'config/database.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_login'] = true;
            $_SESSION['username'] = $row['username'];
            header('Location: admin.php');
            exit;
        }
    }
    $message = "Username atau Password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - SEMESTA BUKU</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">📚 SEMESTA BUKU</a>
        <button id="theme-toggle" class="theme-toggle">🌙 Dark Mode</button>
    </header>

    <div class="container" style="max-width: 400px;">
        <div class="card">
            <h2>Login Admin</h2>
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
                <p style="color:green;">Registrasi berhasil! Silakan login.</p>
            <?php endif; ?>
            <?php if($message): ?><p style="color:red;"><?= $message ?></p><?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Login</button>
            </form>
            <p style="margin-top:1rem; text-align:center;">Belum punya akun? <a href="register.php">Register</a></p>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>