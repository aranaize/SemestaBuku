<?php
include 'config/database.php';
$selected_book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_peminjam']);
    $book_id = (int)$_POST['book_id'];
    $tgl_pinjam = $_POST['tanggal_pinjam'];

    $query = mysqli_query($conn, "INSERT INTO peminjaman (nama_peminjam, book_id, tanggal_pinjam, status) VALUES ('$nama', $book_id, '$tgl_pinjam', 'dipinjam')");
    if($query) {
        mysqli_query($conn, "UPDATE books SET stok = stok - 1 WHERE id = $book_id AND stok > 0");
        $msg = "Permintaan peminjaman berhasil dikirim!";
    } else {
        $msg = "Gagal memproses peminjaman.";
    }
}
$books_query = mysqli_query($conn, "SELECT * FROM books WHERE stok > 0");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman - SemestaBuku</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="nav-brand">🌿 SemestaBuku</a>
        <div class="nav-links">
            <a href="index.php">Katalog</a>
            <a href="peminjaman.php" class="active">Form Peminjaman</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container" style="max-width: 600px;">
            <h2 class="section-title" style="text-align: center;">Form Peminjaman Buku</h2>
            <?php if($msg): ?><p style="color: var(--primary-green); text-align: center; margin-bottom: 15px; font-weight: bold;"><?php echo $msg; ?></p><?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap Peminjam</label>
                    <input type="text" name="nama_peminjam" required placeholder="Masukkan nama Anda...">
                </div>
                <div class="form-group">
                    <label>Pilih Buku</label>
                    <select name="book_id" required>
                        <?php while($b = mysqli_fetch_assoc($books_query)): ?>
                            <option value="<?php echo $b['id']; ?>" <?php if($selected_book_id == $b['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($b['judul']); ?> (Stok: <?php echo $b['stok']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" required value="<?php echo date('Y-m-d'); ?>">
                </div>
                <button type="submit" class="btn-pill" style="width: 100%; text-align: center;">Kirim Pengajuan Pinjam</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 SemestaBuku.</p>
    </footer>
</body>
</html>