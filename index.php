<?php
require_once 'config/database.php';

$query = "SELECT * FROM books ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEMESTA BUKU - Katalog Perpustakaan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header>
        <a href="index.php" class="logo">📚 SEMESTA BUKU</a>
        <nav>
            <button id="theme-toggle" class="theme-toggle">🌙 Dark Mode</button>
            <a href="login.php" class="btn btn-primary">Login Admin</a>
        </nav>
    </header>

    <div class="container">
        <h2>Informasi Ketersediaan Buku</h2>
        <p style="margin-bottom: 1rem; opacity: 0.8;">Cari buku favoritmu di perpustakaan Semesta Buku.</p>

        <div class="search-bar">
            <input type="text" id="search-input" onkeyup="filterBooks()" placeholder="Cari berdasarkan judul atau penulis...">
        </div>

        <div class="grid">
            <?php while($row = mysqli_fetch_assoc($result)) : ?>
                <div class="card book-card">
                    <span class="badge badge-warning"><?= htmlspecialchars($row['kategori']) ?></span>
                    <h3 class="book-title" style="margin: 0.5rem 0;"><?= htmlspecialchars($row['judul']) ?></h3>
                    <p class="book-author" style="margin-bottom: 0.5rem; opacity: 0.8;">Penulis: <?= htmlspecialchars($row['penulis']) ?></p>
                    <p>Stok: <strong><?= $row['stok'] ?></strong></p>
                    <p style="margin-top: 0.5rem;">
                        Status: 
                        <?php if($row['stok'] > 0): ?>
                            <span class="badge badge-success">Tersedia</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Habis</span>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>