-- Tabel Users (Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Buku (Diperbarui dengan ISBN dan Cover)
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(20) DEFAULT '-',
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    cover VARCHAR(255) DEFAULT 'https://via.placeholder.com/150x200?text=No+Cover',
    stok INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Transaksi Peminjaman & Pengembalian
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    nama_peminjam VARCHAR(100) NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE DEFAULT NULL,
    status ENUM('Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- Data Awal Contoh
INSERT INTO books (isbn, judul, penulis, kategori, cover, stok) VALUES
('978-602-291-663-5', 'Laskar Pelangi', 'Andrea Hirata', 'Novel', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1489732961i/1362193.jpg', 3),
('978-602-03-3295-6', 'Bumi', 'Tere Liye', 'Fiksi', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1393805252i/21413849.jpg', 2),
('978-602-06-2339-9', 'Filosofi Teras', 'Henry Manampiring', 'Pengembangan Diri', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1543372224i/42962136.jpg', 0);