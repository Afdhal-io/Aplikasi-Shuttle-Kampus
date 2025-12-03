-- Buat database
CREATE DATABASE IF NOT EXISTS Shuttle_Kampus;
USE Shuttle_Kampus;

-- Tabel Rute (untuk NIM ganjil)
CREATE TABLE IF NOT EXISTS rute (
    rute_id INT AUTO_INCREMENT PRIMARY KEY,
    kode_rute VARCHAR(20) NOT NULL,
    nama_rute VARCHAR(100) NOT NULL,
    titik_berangkat VARCHAR(100) NOT NULL,
    titik_tujuan VARCHAR(100) NOT NULL,
    status_rute ENUM('aktif', 'non-aktif') NOT NULL DEFAULT 'aktif'
);

-- Contoh data awal (opsional)
INSERT INTO rute (kode_rute, nama_rute, titik_berangkat, titik_tujuan, status_rute) VALUES
('CILEDUG-01', 'Ciledug - Kampus Utama', 'Ciledug', 'Kampus Budi Luhur', 'aktif'),
('BINTARO-01', 'Bintaro - Kampus Utama', 'Bintaro', 'Kampus Budi Luhur', 'non-aktif');
