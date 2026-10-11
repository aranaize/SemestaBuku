<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$message = '';

// Tambah Buku Baru
if (isset($_POST['add_book'])) {
    $judul    = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis  = mysqli_real_escape_string($conn, $_POST['penulis']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $isbn     = mysqli_real_escape_string($conn, $_POST['isbn']);
    $stok     = (int)$_POST['stok'];

    // Process Upload Cover
    $cover_name = $_FILES['cover']['name'];
    $cover_tmp  = $_FILES['cover']['tmp_name'];
    $cover_ext  = strtolower(pathinfo($cover_name, PATHINFO_EXTENSION));
    $allowed    = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($cover_ext, $allowed)) {
        $new_cover_name = time() . '_' . uniqid() . '.' . $cover_ext;
        $upload_dir     = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($cover_tmp, $upload_dir . $new_cover_name)) {
            $query = "INSERT INTO books (judul, penulis, kategori, isbn, stok, cover) 
                      VALUES ('$judul', '$penulis', '$kategori', '$isbn', $stok, '$new_cover_name')";
            if (mysqli_query($conn, $query)) {
                $message = "Buku berhasil ditambahkan!";
            } else {
                $message = "Gagal menyimpan data buku.";
            }
        } else {
            $message = "Gagal mengupload gambar cover.";
        }
    } else {
        $message = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";
    }
}

// Hapus Buku
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $res = mysqli_query($conn, "SELECT cover FROM books WHERE id = $id");
    if ($b = mysqli_fetch_assoc($res)) {
        if (!empty($b['cover']) && file_exists('uploads/' . $b['cover'])) {
            unlink('uploads/' . $b['cover']);
        }
    }

    mysqli_query($conn, "DELETE FROM books WHERE id = $id");
    header("Location: admin.php");
    exit();
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
        <a href="index.php" class="logo">SEMESTA BUKU (Admin)</a>
        <div>
            <a href="peminjaman.php" class="btn-pill" style="background:#10b981;">Kelola Peminjaman</a>
            <a href="index.php" class="btn-pill" style="background:transparent; border:1px solid var(--border-color); color:var(--text-main);">Lihat Tampilan Utama</a>
            <a href="logout.php" class="btn-pill" style="background:#ef4444;">Logout</a>
        </div>
    </header>

    <div class="section-wrapper">
        <h2>Kelola Koleksi Buku</h2>

        <?php if ($message): ?>
            <p style="background: #10b981; color: #fff; padding: 0.7rem; border-radius: 6px; margin-top: 1rem;"><?= $message ?></p>
        <?php endif; ?>

        <!-- Form Tambah Buku -->
        <div class="form-card" style="max-width: 100%; margin: 1.5rem 0;">
            <h3>Tambah Buku Baru</h3>
            <form action="" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="penulis" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>ISBN</label>
                    <input type="text" name="isbn" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Stok Buku</label>
                    <input type="number" name="stok" class="form-control" required min="0">
                </div>
                <div class="form-group">
                    <label>Cover Buku (JPG, PNG, WEBP)</label>
                    <input type="file" name="cover" class="form-control" accept="image/*" required>
                </div>
                <div style="grid-column: span 2;">
                    <button type="submit" name="add_book" class="btn-pill" style="width: 100%; padding: 0.8rem;">Simpan Buku</button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Buku -->
        <h3>Daftar Buku (Ditampilkan di Sisi Pengunjung)</h3>
        <table>
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>ISBN</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($books)): ?>
                <tr>
                    <td>
                        <img src="uploads/<?= htmlspecialchars($row['cover']) ?>" class="thumb-img" alt="Cover">
                    </td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['penulis']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= htmlspecialchars($row['isbn']) ?></td>
                    <td>
                        <?php if($row['stok'] > 0): ?>
                            <span style="color:#10b981; font-weight:bold;"><?= $row['stok'] ?> (Tersedia)</span>
                        <?php else: ?>
                            <span style="color:#ef4444; font-weight:bold;">Habis</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_buku.php?id=<?= $row['id'] ?>" class="btn-pill" style="background:#f59e0b; padding:0.4rem 0.8rem; font-size:0.8rem;">Edit</a>
                        <a href="admin.php?delete=<?= $row['id'] ?>" class="btn-danger" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>