<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config/database.php';

// Handle Add Book
if (isset($_POST['tambah_buku'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $stok = (int)$_POST['stok'];
    $cover = mysqli_real_escape_string($conn, $_POST['cover']);
    if(!$cover) $cover = 'default.jpg';

    mysqli_query($conn, "INSERT INTO books (judul, penulis, kategori, isbn, stok, cover) VALUES ('$judul', '$penulis', '$kategori', '$isbn', $stok, '$cover')");
    header("Location: admin.php");
    exit;
}

// Handle Delete Book
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM books WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// Handle Return Book (Pengembalian)
if (isset($_GET['kembalikan'])) {
    $id = (int)$_GET['kembalikan'];
    $trx = mysqli_query($conn, "SELECT book_id FROM peminjaman WHERE id=$id");
    if($t = mysqli_fetch_assoc($trx)) {
        $bid = $t['book_id'];
        mysqli_query($conn, "UPDATE peminjaman SET status='dikembalikan', tanggal_kembali=CURDATE() WHERE id=$id");
        mysqli_query($conn, "UPDATE books SET stok = stok + 1 WHERE id=$bid");
    }
    header("Location: admin.php");
    exit;
}

$books = mysqli_query($conn, "SELECT * FROM books");
$peminjaman = mysqli_query($conn, "SELECT peminjaman.*, books.judul FROM peminjaman JOIN books ON peminjaman.book_id = books.id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SemestaBuku</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="admin.php" class="nav-brand">🌿 Dashboard Admin SemestaBuku</a>
        <div class="nav-links">
            <a href="index.php" target="_blank">Lihat Website</a>
            <a href="logout.php" class="btn-pill" style="background-color: #d9534f;">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="section-title">Tambah Buku Baru</h2>
        <div class="form-container" style="max-width: 100%; margin-bottom: 40px;">
            <form method="POST">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
                    <input type="text" name="judul" required placeholder="Judul Buku">
                    <input type="text" name="penulis" required placeholder="Penulis">
                    <input type="text" name="kategori" required placeholder="Kategori">
                    <input type="text" name="isbn" required placeholder="ISBN">
                    <input type="number" name="stok" required placeholder="Stok">
                    <input type="text" name="cover" placeholder="URL Cover Buku">
                </div>
                <button type="submit" name="tambah_buku" class="btn-pill" style="margin-top: 20px;">Tambah Buku</button>
            </form>
        </div>

        <h2 class="section-title">Kelola Katalog Buku</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>ISBN</th><th>Stok</th><th>Aksi</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($books)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['judul']); ?></td>
                    <td><?php echo htmlspecialchars($row['penulis']); ?></td>
                    <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                    <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                    <td><?php echo $row['stok']; ?></td>
                    <td>
                        <a href="edit_buku.php?id=<?php echo $row['id']; ?>" style="color: var(--primary-green); font-weight:600; text-decoration:none; margin-right: 10px;">Edit</a>
                        <a href="admin.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus buku ini?')" style="color: #d9534f; font-weight:600; text-decoration:none;">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <h2 class="section-title">Daftar Peminjaman & Pengembalian</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th><th>Nama Peminjam</th><th>Judul Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th>
                </tr>
                <?php while($p = mysqli_fetch_assoc($peminjaman)): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['nama_peminjam']); ?></td>
                    <td><?php echo htmlspecialchars($p['judul']); ?></td>
                    <td><?php echo $p['tanggal_pinjam']; ?></td>
                    <td><?php echo $p['tanggal_kembali'] ? $p['tanggal_kembali'] : '-'; ?></td>
                    <td><span style="background: var(--sage-light); padding: 5px 12px; border-radius: 20px; color: var(--primary-green); font-weight:600;"><?php echo $p['status']; ?></span></td>
                    <td>
                        <?php if($p['status'] == 'dipinjam'): ?>
                            <a href="admin.php?kembalikan=<?php echo $p['id']; ?>" onclick="return confirm('Konfirmasi buku dikembalikan?')" style="color: var(--primary-green); font-weight:600; text-decoration:none;">Kembalikan</a>
                        <?php else: ?>
                            <span style="color: var(--text-muted);">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 SemestaBuku Admin Panel.</p>
    </footer>
</body>
</html>