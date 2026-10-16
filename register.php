<?php
include 'config/database.php';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        $query = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if ($query) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Terjadi kesalahan sistem.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Admin - SemestaBuku</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="nav-brand">🌿 SemestaBuku</a>
        <div class="nav-links">
            <a href="login.php">Login Admin</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2 class="section-title" style="text-align: center;">Registrasi Admin</h2>
            <?php if($error): ?><p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error; ?></p><?php endif; ?>
            <?php if($success): ?><p style="color: green; text-align: center; margin-bottom: 15px;"><?php echo $success; ?></p><?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username Baru</label>
                    <input type="text" name="username" required placeholder="Buat username...">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Buat password...">
                </div>
                <button type="submit" class="btn-pill" style="width: 100%; text-align: center;">Daftar Akun</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 SemestaBuku.</p>
    </footer>
</body>
</html>