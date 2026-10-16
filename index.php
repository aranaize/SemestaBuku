<?php
include 'config/database.php';
$query = mysqli_query($conn, "SELECT * FROM books");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SemestaBuku - Beranda</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="nav-brand">🌿 SemestaBuku</a>
        <div class="nav-links">
            <a href="index.php" class="active">Katalog</a>
            <a href="peminjaman.php">Form Peminjaman</a>
            <a href="login.php" class="btn-pill">Login Admin</a>
        </div>
    </nav>

    <div class="hero-section">
        <h1>Jelajahi Dunia Lewat Setiap Halaman Buku</h1>
        <p>Temukan koleksi buku inspiratif dengan sistem peminjaman yang mudah dan cepat.</p>
    </div>

    <div class="search-pill-container">
        <input type="text" id="searchBook" placeholder="Cari judul buku atau penulis...">
        <button class="btn-pill">Cari Buku</button>
    </div>

    <div class="container">
        <h2 class="section-title">Katalog Buku Pilihan</h2>
        <div class="grid-catalog">
            <?php while($row = mysqli_fetch_assoc($query)): ?>
            <div class="card-book">
                <img src="<?php echo htmlspecialchars($row['cover']); ?>" alt="Cover Buku" onerror="this.src='https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=500&q=80'">
                <div class="card-body">
                    <div>
                        <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                        <p><?php echo htmlspecialchars($row['penulis']); ?> • Stok: <?php echo $row['stok']; ?></p>
                        <p style="font-size: 12px; color: var(--sage-accent);">ISBN: <?php echo htmlspecialchars($row['isbn']); ?></p>
                    </div>
                    <a href="peminjaman.php?book_id=<?php echo $row['id']; ?>" class="btn-pill" style="text-align: center;">Pinjam Buku</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 SemestaBuku. All Rights Reserved.</p>
    </footer>
    <script src="assets/js/main.js"></script>
</body>
</html>