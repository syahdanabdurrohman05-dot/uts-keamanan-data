CREATE DATABASE IF NOT EXISTS db_warung_kripto;
USE db_warung_kripto;

CREATE TABLE IF NOT EXISTS data_rahasia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan TEXT NOT NULL,
    keterangan TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);