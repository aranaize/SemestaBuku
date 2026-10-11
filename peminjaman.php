<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$message = '';

// Catat Peminjaman Baru
if (isset($_POST['add_loan'])) {
    $nama_peminjam  = mysqli_real_escape_string($conn, $_POST['nama_peminjam']);
    $book_id        = (int)$_POST['book_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];

    // Cek stok buku
    $check_book = mysqli_query($conn, "SELECT stok FROM books WHERE id = $book_id");
    $b = mysqli_fetch_assoc($check_book);

    if ($b && $b['stok'] > 0) {
        // Insert data peminjaman
        $insert = "INSERT INTO peminjaman (nama_peminjam, book_id, tanggal_pinjam, status) 
                   VALUES ('$nama_peminjam', $book_id, '$tanggal_pinjam', 'dipinjam')";
        
        if (mysqli_query($conn, $insert)) {
            // Kurangi stok buku 1 unit
            mysqli_query($conn, "UPDATE books SET stok = stok - 1 WHERE id = $book_id");
            $message = "Peminjaman berhasil dicatat!";
        }
    } else {
        $message = "Gagal! Stok buku habis.";
    }
}

// Proses Pengembalian Buku
if (isset($_GET['return_id'])) {
    $loan_id = (int)$_GET['return_id'];
    $today   = date('Y-m-d');

    // Ambil book_id dari data peminjaman
    $loan_res = mysqli_query($conn, "SELECT book_id, status FROM peminjaman WHERE id = $loan_id");
    $loan = mysqli_fetch_assoc($loan_res);

    if ($loan && $loan['status'] == 'dipinjam') {
        // Update status peminjaman menjadi dikembalikan
        mysqli_query($conn, "UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali = '$today' WHERE id = $loan_id");
        
        // Tambahkan kembali stok buku 1 unit
        mysqli_query($conn, "UPDATE books SET stok = stok + 1 WHERE id = {$loan['book_id']}");
        header("Location: peminjaman.php");
        exit();
    }
}

// Ambil data buku yang stoknya tersedia untuk dropdown
$available_books = mysqli_query($conn, "SELECT * FROM books WHERE stok > 0");

// Ambil riwayat peminjaman
$loans = mysqli_query($conn, "SELECT peminjaman.*, books.judul 
                              FROM peminjaman 
                              JOIN books ON peminjaman.book_id = books.id 
                              ORDER BY peminjaman.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Peminjaman - SEMESTA BUKU</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a href="admin.php" class="logo">SEMESTA BUKU (Admin)</a>
        <div>
            <a href="admin.php" class="btn-pill" style="background:transparent; border:1px solid var(--border-color); color:var(--text-main);">Kelola Buku</a>
            <a href="logout.php" class="btn-pill" style="background:#ef4444;">Logout</a>
        </div>
    </header>

    <div class="section-wrapper">
        <h2>Pencatatan Peminjaman & Pengembalian Buku</h2>

        <?php if ($message): ?>
            <p style="background: #10b981; color: #fff; padding: 0.7rem; border-radius: 6px; margin-top: 1rem;"><?= $message ?></p>
        <?php endif; ?>

        <!-- Form Tambah Peminjaman -->
        <div class="form-card" style="max-width: 100%; margin: 1.5rem 0;">
            <h3>Input Transaksi Peminjaman Baru</h3>
            <form action="" method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label>Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control" required placeholder="Nama Anggota / Pembaca">
                </div>
                <div class="form-group">
                    <label>Pilih Buku</label>
                    <select name="book_id" class="form-control" required>
                        <option value="">-- Pilih Buku Tersedia --</option>
                        <?php while($bk = mysqli_fetch_assoc($available_books)): ?>
                            <option value="<?= $bk['id'] ?>"><?= htmlspecialchars($bk['judul']) ?> (Stok: <?= $bk['stok'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div style="grid-column: span 3;">
                    <button type="submit" name="add_loan" class="btn-pill" style="width: 100%; padding: 0.8rem;">Simpan Transaksi Peminjaman</button>
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat Peminjaman -->
        <h3>Riwayat Peminjaman & Pengembalian</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($loans)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= $row['tanggal_pinjam'] ?></td>
                    <td><?= $row['tanggal_kembali'] ? $row['tanggal_kembali'] : '-' ?></td>
                    <td>
                        <?php if($row['status'] == 'dipinjam'): ?>
                            <span style="color:#f59e0b; font-weight:bold;">Dipinjam</span>
                        <?php else: ?>
                            <span style="color:#10b981; font-weight:bold;">Dikembalikan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($row['status'] == 'dipinjam'): ?>
                            <a href="peminjaman.php?return_id=<?= $row['id'] ?>" class="btn-pill" style="background:#10b981; padding:0.4rem 0.8rem; font-size:0.8rem;" onclick="return confirm('Proses pengembalian buku ini?')">Tandai Kembali</a>
                        <?php else: ?>
                            <span style="color:var(--text-muted); font-size:0.85rem;">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>