<?php
session_start();
include 'config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: admin.php");
            exit;
        }
    }
    $error = "Username atau Password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SemestaBuku</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="nav-brand">🌿 SemestaBuku</a>
        <div class="nav-links">
            <a href="index.php">Kembali ke Beranda</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2 class="section-title" style="text-align: center;">Login Admin</h2>
            <?php if($error): ?><p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error; ?></p><?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Masukkan username...">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password...">
                </div>
                <button type="submit" class="btn-pill" style="width: 100%; text-align: center;">Masuk Dashboard</button>
            </form>
            <div style="text-align: center; margin-top: 15px;">
                <a href="register.php" style="color: var(--primary-green); text-decoration: none; font-size: 14px;">Belum punya akun? Registrasi</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 SemestaBuku.</p>
    </footer>
</body>
</html>