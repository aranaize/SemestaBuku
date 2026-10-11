<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM books WHERE id = $id");
$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location: admin.php");
    exit();
}

$message = '';

if (isset($_POST['update_book'])) {
    $judul    = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis  = mysqli_real_escape_string($conn, $_POST['penulis']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $isbn     = mysqli_real_escape_string($conn, $_POST['isbn']);
    $stok     = (int)$_POST['stok'];

    $cover_name = $_FILES['cover']['name'];

    // Jika admin mengganti foto cover
    if (!empty($cover_name)) {
        $cover_tmp = $_FILES['cover']['tmp_name'];
        $cover_ext = strtolower(pathinfo($cover_name, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($cover_ext, $allowed)) {
            $new_cover_name = time() . '_' . uniqid() . '.' . $cover_ext;
            
            // Hapus foto lama
            if (!empty($book['cover']) && file_exists('uploads/' . $book['cover'])) {
                unlink('uploads/' . $book['cover']);
            }

            move_uploaded_file($cover_tmp, 'uploads/' . $new_cover_name);
            $query_cover = ", cover='$new_cover_name'";
        } else {
            $message = "Format gambar tidak didukung!";
        }
    } else {
        $query_cover = "";
    }

    if (!$message) {
        $update = "UPDATE books SET 
                   judul='$judul', 
                   penulis='$penulis', 
                   kategori='$kategori', 
                   isbn='$isbn', 
                   stok=$stok $query_cover 
                   WHERE id=$id";

        if (mysqli_query($conn, $update)) {
            header("Location: admin.php");
            exit();
        } else {
            $message = "Gagal memperbarui data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku - SEMESTA BUKU</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a href="admin.php" class="logo">SEMESTA BUKU (Admin)</a>
        <a href="admin.php" class="btn-pill">Kembali ke Dashboard</a>
    </header>

    <div class="section-wrapper">
        <div class="form-card" style="max-width: 600px; margin: 2rem auto;">
            <h3>Edit Data Buku</h3>
            <?php if ($message): ?>
                <p style="color: #ef4444; margin-top: 0.5rem;"><?= $message ?></p>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($book['judul']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($book['penulis']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?= htmlspecialchars($book['kategori']) ?>" required>
                </div>
                <div class="form-group">
                    <label>ISBN</label>
                    <input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($book['isbn']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Stok Buku</label>
                    <input type="number" name="stok" class="form-control" value="<?= $book['stok'] ?>" required min="0">
                </div>
                <div class="form-group">
                    <label>Ganti Cover Buku (Opsional)</label>
                    <input type="file" name="cover" class="form-control" accept="image/*">
                    <p style="font-size:0.8rem; color:var(--text-muted); margin-top:0.3rem;">Cover saat ini: <?= htmlspecialchars($book['cover']) ?></p>
                </div>
                <button type="submit" name="update_book" class="btn-pill" style="width: 100%; padding: 0.8rem;">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>