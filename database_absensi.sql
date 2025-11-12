-- ==================================================
-- Reset Database (opsional untuk development)
-- ==================================================
DROP DATABASE IF EXISTS absensi_kampus;
CREATE DATABASE absensi_kampus CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE absensi_kampus;

-- ==================================================
-- Tabel Master
-- ==================================================

-- Tabel Mahasiswa
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    face_id TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- Tabel Admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- Tabel Mata Kuliah
CREATE TABLE mata_kuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(10) UNIQUE NOT NULL,
    nama_mk VARCHAR(100) NOT NULL,
    sks INT NOT NULL CHECK (sks BETWEEN 1 AND 6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- Tabel Kelas
CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(10) NOT NULL,
    id_mk INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (id_mk) REFERENCES mata_kuliah(id) ON DELETE CASCADE
);

-- Tabel Jadwal
CREATE TABLE jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_kelas INT NOT NULL,
    hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    lokasi_lat DOUBLE NOT NULL,
    lokasi_long DOUBLE NOT NULL,
    radius_meter INT DEFAULT 50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id) ON DELETE CASCADE
);

-- Relasi Mahasiswa ↔ Kelas
CREATE TABLE mahasiswa_kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_kelas INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    UNIQUE(id_mahasiswa, id_kelas),
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id) ON DELETE CASCADE
);

-- ==================================================
-- Tabel Transaksi
-- ==================================================

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
    deleted_at TIMESTAMP NULL,
    UNIQUE(id_mahasiswa, id_jadwal, tanggal),
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id) ON DELETE CASCADE
);

-- Tabel Pengajuan Izin
CREATE TABLE pengajuan_izin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_jadwal INT NOT NULL,
    tanggal DATE NOT NULL,
    alasan TEXT NOT NULL,
    bukti_url TEXT,
    status ENUM('pending','disetujui','ditolak') DEFAULT 'pending',
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES admin(id) ON DELETE SET NULL
);

-- ENUM untuk notifikasi tidak ada di MySQL, jadi langsung ENUM di kolom
CREATE TABLE notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    type ENUM('absensi_dibuka','izin_disetujui','izin_ditolak','pengingat_absen') NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE
);

-- ==================================================
-- Index
-- ==================================================
CREATE INDEX idx_mahasiswa_nim ON mahasiswa(nim);
CREATE INDEX idx_mahasiswa_email ON mahasiswa(email);
CREATE INDEX idx_mk_kode ON mata_kuliah(kode_mk);
CREATE INDEX idx_kelas_mk ON kelas(id_mk);
CREATE INDEX idx_jadwal_kelas ON jadwal(id_kelas);
CREATE INDEX idx_absensi_tanggal ON absensi(tanggal);
CREATE INDEX idx_absensi_status ON absensi(status);
CREATE INDEX idx_pengajuan_status ON pengajuan_izin(status);
CREATE INDEX idx_notifikasi_mhs ON notifikasi(id_mahasiswa);

-- ==================================================
-- View: Rekap Kehadiran
-- ==================================================
CREATE OR REPLACE VIEW v_rekap_kehadiran AS
SELECT
    m.nim,
    m.nama AS nama_mahasiswa,
    mk.kode_mk,
    mk.nama_mk,
    k.nama_kelas,
    SUM(a.status = 'Hadir') AS total_hadir,
    SUM(a.status = 'Izin') AS total_izin,
    SUM(a.status = 'Sakit') AS total_sakit,
    SUM(a.status = 'Alpha') AS total_alpha,
    COUNT(a.id) AS total_pertemuan
FROM mahasiswa m
JOIN mahasiswa_kelas mkls ON m.id = mkls.id_mahasiswa AND mkls.deleted_at IS NULL
JOIN kelas k ON mkls.id_kelas = k.id AND k.deleted_at IS NULL
JOIN mata_kuliah mk ON k.id_mk = mk.id AND mk.deleted_at IS NULL
LEFT JOIN jadwal j ON k.id = j.id_kelas AND j.deleted_at IS NULL
LEFT JOIN absensi a ON m.id = a.id_mahasiswa AND j.id = a.id_jadwal AND a.deleted_at IS NULL
WHERE m.deleted_at IS NULL
GROUP BY m.nim, m.nama, mk.kode_mk, mk.nama_mk, k.nama_kelas
ORDER BY m.nim, mk.kode_mk;

-- ==================================================
-- View: Absensi Detail
-- ==================================================
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
