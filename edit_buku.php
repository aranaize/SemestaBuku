<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = mysqli_query($conn, "SELECT * FROM books WHERE id=$id");
$buku = mysqli_fetch_assoc($result);

if (!$buku) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $stok = (int)$_POST['stok'];
    $cover = mysqli_real_escape_string($conn, $_POST['cover']);

    mysqli_query($conn, "UPDATE books SET judul='$judul', penulis='$penulis', kategori='$kategori', isbn='$isbn', stok=$stok, cover='$cover' WHERE id=$id");
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - SemestaBuku</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="admin.php" class="nav-brand">🌿 Edit Buku SemestaBuku</a>
        <div class="nav-links">
            <a href="admin.php">Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container" style="max-width: 600px;">
            <h2 class="section-title" style="text-align: center;">Edit Data Buku</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" value="<?php echo htmlspecialchars($buku['judul']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="penulis" value="<?php echo htmlspecialchars($buku['penulis']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" value="<?php echo htmlspecialchars($buku['kategori']); ?>" required>
                </div>
                <div class="form-group">
                    <label>ISBN</label>
                    <input type="text" name="isbn" value="<?php echo htmlspecialchars($buku['isbn']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?php echo $buku['stok']; ?>" required>
                </div>
                <div class="form-group">
                    <label>URL Cover Buku</label>
                    <input type="text" name="cover" value="<?php echo htmlspecialchars($buku['cover']); ?>">
                </div>
                <button type="submit" class="btn-pill" style="width: 100%; text-align: center;">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 SemestaBuku.</p>
    </footer>
</body>
</html>