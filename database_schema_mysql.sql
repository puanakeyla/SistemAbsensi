-- ========================================
-- DATABASE ABSENSI KAMPUS - MySQL Version
-- Converted from PostgreSQL
-- ========================================

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS absensi_kampus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE absensi_kampus;

-- Hapus tabel jika sudah ada (untuk fresh install)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS notifikasi;
DROP TABLE IF EXISTS pengajuan_izin;
DROP TABLE IF EXISTS absensi;
DROP TABLE IF EXISTS mahasiswa_kelas;
DROP TABLE IF EXISTS jadwal;
DROP TABLE IF EXISTS kelas;
DROP TABLE IF EXISTS mata_kuliah;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS mahasiswa;
DROP VIEW IF EXISTS v_rekap_kehadiran;
DROP VIEW IF EXISTS v_absensi_detail;
SET FOREIGN_KEY_CHECKS = 1;

-- ==========================
-- Tabel Master
-- ==========================

-- Tabel Mahasiswa
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    face_id TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_nim (nim),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Mata Kuliah
CREATE TABLE mata_kuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(10) UNIQUE NOT NULL,
    nama_mk VARCHAR(100) NOT NULL,
    sks INT NOT NULL CHECK (sks BETWEEN 1 AND 6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_kode (kode_mk)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Kelas
CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(10) NOT NULL,
    id_mk INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (id_mk) REFERENCES mata_kuliah(id) ON DELETE CASCADE,
    INDEX idx_mk (id_mk)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Jadwal
CREATE TABLE jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_kelas INT NOT NULL,
    hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    lokasi_lat DOUBLE,
    lokasi_long DOUBLE,
    radius_meter INT DEFAULT 50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id) ON DELETE CASCADE,
    INDEX idx_kelas (id_kelas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Relasi Mahasiswa ↔ Kelas (N:M)
CREATE TABLE mahasiswa_kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_kelas INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_mhs_kelas (id_mahasiswa, id_kelas),
    INDEX idx_mahasiswa (id_mahasiswa),
    INDEX idx_kelas (id_kelas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================
-- Tabel Transaksi
-- ==========================

-- Tabel Absensi
CREATE TABLE absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_jadwal INT NOT NULL,
    tanggal DATE NOT NULL,
    waktu_absen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Hadir','Izin','Sakit','Alpha') NOT NULL,
    lokasi_lat DOUBLE,
    lokasi_long DOUBLE,
    face_match_score DECIMAL(5,2),
    device_info TEXT,
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id) ON DELETE CASCADE,
    UNIQUE KEY unique_absen (id_mahasiswa, id_jadwal, tanggal),
    INDEX idx_tanggal (tanggal),
    INDEX idx_status (status),
    INDEX idx_mhs_tgl (id_mahasiswa, tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Pengajuan Izin
CREATE TABLE pengajuan_izin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_jadwal INT NOT NULL,
    tanggal DATE NOT NULL,
    alasan TEXT NOT NULL,
    bukti_url TEXT,
    status ENUM('pending', 'disetujui', 'ditolak') NOT NULL DEFAULT 'pending',
    verified_by INT,
    verified_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES admin(id) ON DELETE SET NULL,
    INDEX idx_mhs_tgl (id_mahasiswa, tanggal),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Notifikasi
CREATE TABLE notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    type ENUM('absensi_dibuka', 'izin_disetujui', 'izin_ditolak', 'pengingat_absen') NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    INDEX idx_mahasiswa (id_mahasiswa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================
-- Views
-- ==========================

-- View: Rekap Kehadiran
CREATE OR REPLACE VIEW v_rekap_kehadiran AS
SELECT
    m.nim,
    m.nama AS nama_mahasiswa,
    mk.kode_mk,
    mk.nama_mk,
    k.nama_kelas,
    COUNT(CASE WHEN a.status = 'Hadir' THEN 1 END) AS total_hadir,
    COUNT(CASE WHEN a.status = 'Izin' THEN 1 END) AS total_izin,
    COUNT(CASE WHEN a.status = 'Sakit' THEN 1 END) AS total_sakit,
    COUNT(CASE WHEN a.status = 'Alpha' THEN 1 END) AS total_alpha,
    COUNT(a.id) AS total_pertemuan
FROM mahasiswa m
JOIN mahasiswa_kelas mkls ON m.id = mkls.id_mahasiswa AND mkls.deleted_at IS NULL
JOIN kelas k ON mkls.id_kelas = k.id AND k.deleted_at IS NULL
JOIN mata_kuliah mk ON k.id_mk = mk.id AND mk.deleted_at IS NULL
LEFT JOIN jadwal j ON k.id = j.id_kelas AND j.deleted_at IS NULL
LEFT JOIN absensi a ON m.id = a.id_mahasiswa 
    AND j.id = a.id_jadwal 
    AND a.deleted_at IS NULL
WHERE m.deleted_at IS NULL
GROUP BY m.nim, m.nama, mk.kode_mk, mk.nama_mk, k.nama_kelas
ORDER BY m.nim, mk.kode_mk;

-- View: Absensi Detail
CREATE OR REPLACE VIEW v_absensi_detail AS
SELECT
    m.nim,
    m.nama AS nama_mahasiswa,
    mk.nama_mk,
    k.nama_kelas,
    j.hari,
    j.jam_mulai,
    j.jam_selesai,
    a.tanggal,
    a.status,
    a.lokasi_lat,
    a.lokasi_long,
    a.face_match_score,
    a.waktu_absen
FROM absensi a
JOIN mahasiswa m ON a.id_mahasiswa = m.id AND m.deleted_at IS NULL
JOIN jadwal j ON a.id_jadwal = j.id AND j.deleted_at IS NULL
JOIN kelas k ON j.id_kelas = k.id AND k.deleted_at IS NULL
JOIN mata_kuliah mk ON k.id_mk = mk.id AND mk.deleted_at IS NULL
WHERE a.deleted_at IS NULL
ORDER BY a.tanggal DESC, m.nim;
