<?php
require_once 'config/database.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($checkUser) > 0) {
        $message = "Username sudah digunakan!";
    } else {
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $query)) {
            header('Location: login.php?msg=registered');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Admin - SEMESTA BUKU</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">📚 SEMESTA BUKU</a>
        <button id="theme-toggle" class="theme-toggle">🌙 Dark Mode</button>
    </header>

    <div class="container" style="max-width: 400px;">
        <div class="card">
            <h2>Register Admin</h2>
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
                <button type="submit" class="btn btn-primary" style="width:100%;">Daftar</button>
            </form>
            <p style="margin-top:1rem; text-align:center;">Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>