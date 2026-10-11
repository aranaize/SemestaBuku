<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header('Location: login.php');
    exit;
}
require_once 'config/database.php';

// Tambah Buku
if (isset($_POST['add_book'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok = (int)$_POST['stok'];

    mysqli_query($conn, "INSERT INTO books (judul, penulis, kategori, stok) VALUES ('$judul', '$penulis', '$kategori', '$stok')");
    header('Location: admin.php');
}

// Hapus Buku
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM books WHERE id = $id");
    header('Location: admin.php');
}

$books = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - SEMESTA BUKU</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a href="admin.php" class="logo">📚 Dashboard Admin</a>
        <nav>
            <button id="theme-toggle" class="theme-toggle">🌙 Dark Mode</button>
            <a href="logout.php" class="btn btn-danger">Logout (<?= $_SESSION['username'] ?>)</a>
        </nav>
    </header>

    <div class="container">
        <div class="card" style="margin-bottom: 2rem;">
            <h3>Tambah Buku Baru</h3>
            <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <input type="text" name="judul" placeholder="Judul Buku" required style="width:100%; padding:0.5rem;">
                </div>
                <div>
                    <input type="text" name="penulis" placeholder="Penulis" required style="width:100%; padding:0.5rem;">
                </div>
                <div>
                    <input type="text" name="kategori" placeholder="Kategori" required style="width:100%; padding:0.5rem;">
                </div>
                <div>
                    <input type="number" name="stok" placeholder="Jumlah Stok" required style="width:100%; padding:0.5rem;">
                </div>
                <button type="submit" name="add_book" class="btn btn-primary">Tambah</button>
            </form>
        </div>

        <h3>Daftar Koleksi Buku</h3>
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($books)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['penulis']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>
                        <a href="admin.php?delete=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>