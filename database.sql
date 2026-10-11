-- Tabel Users (Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Buku
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    stok INT NOT NULL DEFAULT 1,
    status ENUM('Tersedia', 'Dipinjam', 'Habis') DEFAULT 'Tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Data Awal Buku
INSERT INTO books (judul, penulis, kategori, stok, status) VALUES
('Laskar Pelangi', 'Andrea Hirata', 'Novel', 3, 'Tersedia'),
('Bumi', 'Tere Liye', 'Fiksi', 2, 'Tersedia'),
('Filosofi Teras', 'Henry Manampiring', 'Pengembangan Diri', 0, 'Habis'),
('Atomic Habits', 'James Clear', 'Pengembangan Diri', 1, 'Tersedia');