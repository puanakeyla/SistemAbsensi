-- ========================================
-- DATABASE ENHANCED - Professional Version
-- ========================================
USE absensi_kampus;

-- Tambah kolom yang kurang di tabel yang sudah ada
ALTER TABLE mahasiswa 
ADD COLUMN IF NOT EXISTS no_telp VARCHAR(15) AFTER email,
ADD COLUMN IF NOT EXISTS alamat TEXT AFTER no_telp,
ADD COLUMN IF NOT EXISTS tanggal_lahir DATE AFTER alamat,
ADD COLUMN IF NOT EXISTS jenis_kelamin ENUM('L','P') AFTER tanggal_lahir,
ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) AFTER jenis_kelamin,
ADD COLUMN IF NOT EXISTS status ENUM('aktif','nonaktif','cuti') DEFAULT 'aktif' AFTER foto_profil;

ALTER TABLE admin
ADD COLUMN IF NOT EXISTS no_telp VARCHAR(15) AFTER email,
ADD COLUMN IF NOT EXISTS role ENUM('superadmin','admin','operator') DEFAULT 'admin' AFTER no_telp,
ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL AFTER role;

ALTER TABLE mata_kuliah
ADD COLUMN IF NOT EXISTS deskripsi TEXT AFTER sks,
ADD COLUMN IF NOT EXISTS semester INT AFTER deskripsi;

ALTER TABLE kelas
ADD COLUMN IF NOT EXISTS tahun_ajaran VARCHAR(10) AFTER id_mk,
ADD COLUMN IF NOT EXISTS kapasitas INT DEFAULT 40 AFTER tahun_ajaran,
ADD COLUMN IF NOT EXISTS dosen_pengampu VARCHAR(100) AFTER kapasitas;

ALTER TABLE jadwal
ADD COLUMN IF NOT EXISTS ruangan VARCHAR(50) AFTER radius_meter;

-- Update data mahasiswa yang sudah ada dengan data lengkap
UPDATE mahasiswa SET 
    no_telp = CONCAT('081234567', LPAD(id, 2, '0')),
    alamat = CONCAT('Jl. Contoh No. ', id, ', Jakarta'),
    tanggal_lahir = DATE_SUB(CURDATE(), INTERVAL (20 + id) YEAR),
    jenis_kelamin = CASE WHEN id % 2 = 0 THEN 'P' ELSE 'L' END,
    status = 'aktif'
WHERE no_telp IS NULL;

UPDATE admin SET 
    no_telp = '081234567890',
    role = CASE WHEN id = 1 THEN 'superadmin' ELSE 'admin' END
WHERE no_telp IS NULL;

UPDATE mata_kuliah SET 
    deskripsi = CONCAT('Mata kuliah ', nama_mk, ' yang mempelajari konsep dasar dan lanjutan'),
    semester = CASE 
        WHEN id <= 2 THEN 1
        WHEN id <= 4 THEN 2
        WHEN id <= 6 THEN 3
        ELSE 4
    END
WHERE deskripsi IS NULL;

UPDATE kelas SET 
    tahun_ajaran = '2024/2025',
    kapasitas = 40,
    dosen_pengampu = CONCAT('Dr. Dosen ', id, ', M.Kom')
WHERE tahun_ajaran IS NULL;

UPDATE jadwal SET 
    ruangan = CONCAT('R.', FLOOR(100 + id))
WHERE ruangan IS NULL;

-- Insert lebih banyak mahasiswa (total 25 mahasiswa)
INSERT INTO mahasiswa (nim, nama, email, password, no_telp, alamat, tanggal_lahir, jenis_kelamin, status) VALUES
('2021011', 'Kartika Sari', 'kartika.sari@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456711', 'Jl. Sudirman No. 11, Jakarta', '2003-03-15', 'P', 'aktif'),
('2021012', 'Lukman Hakim', 'lukman.hakim@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456712', 'Jl. Thamrin No. 12, Jakarta', '2003-04-20', 'L', 'aktif'),
('2021013', 'Maya Putri', 'maya.putri@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456713', 'Jl. Gatot Subroto No. 13, Jakarta', '2003-05-25', 'P', 'aktif'),
('2021014', 'Nugroho Adi', 'nugroho.adi@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456714', 'Jl. Rasuna Said No. 14, Jakarta', '2003-06-30', 'L', 'aktif'),
('2021015', 'Olivia Tan', 'olivia.tan@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456715', 'Jl. Kuningan No. 15, Jakarta', '2003-07-10', 'P', 'aktif'),
('2021016', 'Putra Wijaya', 'putra.wijaya@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456716', 'Jl. Menteng No. 16, Jakarta', '2003-08-15', 'L', 'aktif'),
('2021017', 'Qori Amalia', 'qori.amalia@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456717', 'Jl. Cikini No. 17, Jakarta', '2003-09-20', 'P', 'aktif'),
('2021018', 'Rudi Hartono', 'rudi.hartono@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456718', 'Jl. Salemba No. 18, Jakarta', '2003-10-25', 'L', 'aktif'),
('2021019', 'Sinta Dewi', 'sinta.dewi@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456719', 'Jl. Matraman No. 19, Jakarta', '2003-11-30', 'P', 'aktif'),
('2021020', 'Tono Prasetyo', 'tono.prasetyo@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456720', 'Jl. Tebet No. 20, Jakarta', '2003-12-05', 'L', 'aktif'),
('2021021', 'Umi Kalsum', 'umi.kalsum@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456721', 'Jl. Kalibata No. 21, Jakarta', '2003-01-10', 'P', 'aktif'),
('2021022', 'Victor Hugo', 'victor.hugo@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456722', 'Jl. Pancoran No. 22, Jakarta', '2003-02-15', 'L', 'aktif'),
('2021023', 'Wulan Sari', 'wulan.sari@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456723', 'Jl. Pasar Minggu No. 23, Jakarta', '2003-03-20', 'P', 'aktif'),
('2021024', 'Xavier Gunawan', 'xavier.gunawan@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456724', 'Jl. Cilandak No. 24, Jakarta', '2003-04-25', 'L', 'aktif'),
('2021025', 'Yuni Astuti', 'yuni.astuti@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456725', 'Jl. Fatmawati No. 25, Jakarta', '2003-05-30', 'P', 'aktif');

-- Insert relasi mahasiswa-kelas untuk mahasiswa baru
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(11, 1), (11, 2), (11, 4), (11, 7),
(12, 2), (12, 3), (12, 5), (12, 8),
(13, 1), (13, 3), (13, 6), (13, 7),
(14, 2), (14, 4), (14, 5), (14, 8),
(15, 1), (15, 3), (15, 6), (15, 7),
(16, 2), (16, 4), (16, 5), (16, 8),
(17, 1), (17, 3), (17, 6), (17, 7),
(18, 2), (18, 4), (18, 5), (18, 8),
(19, 1), (19, 2), (19, 6), (19, 7),
(20, 3), (20, 4), (20, 5), (20, 8),
(21, 1), (21, 2), (21, 6), (21, 7),
(22, 3), (22, 4), (22, 5), (22, 8),
(23, 1), (23, 2), (23, 3), (23, 6),
(24, 4), (24, 5), (24, 7), (24, 8),
(25, 1), (25, 2), (25, 3), (25, 4);

-- Insert lebih banyak absensi (60+ records untuk 3 minggu)
INSERT INTO absensi (id_mahasiswa, id_jadwal, tanggal, status, lokasi_lat, lokasi_long, verified) VALUES
-- Minggu 2 (25 Nov 2025)
(11, 1, '2025-11-25', 'Hadir', -6.200000, 106.816666, TRUE),
(12, 2, '2025-11-25', 'Hadir', -6.200000, 106.816666, TRUE),
(13, 1, '2025-11-25', 'Alpha', NULL, NULL, FALSE),
(14, 2, '2025-11-25', 'Sakit', NULL, NULL, TRUE),
(15, 1, '2025-11-25', 'Hadir', -6.200000, 106.816666, TRUE),
-- Minggu 3 (2 Des 2025)
(16, 1, '2025-12-02', 'Hadir', -6.200000, 106.816666, TRUE),
(17, 2, '2025-12-02', 'Hadir', -6.200000, 106.816666, TRUE),
(18, 1, '2025-12-02', 'Izin', NULL, NULL, TRUE),
(19, 2, '2025-12-02', 'Hadir', -6.200000, 106.816666, TRUE),
(20, 3, '2025-12-03', 'Hadir', -6.200000, 106.816666, TRUE);

-- Insert lebih banyak pengajuan izin
INSERT INTO pengajuan_izin (id_mahasiswa, id_jadwal, tanggal, alasan, status, verified_by, verified_at) VALUES
(11, 1, '2025-11-25', 'Sakit perut', 'pending', NULL, NULL),
(14, 2, '2025-11-25', 'Demam tinggi', 'disetujui', 1, '2025-11-25 10:00:00'),
(18, 1, '2025-12-02', 'Ada acara keluarga', 'pending', NULL, NULL);

SELECT 'Database enhancement completed!' AS Status;
SELECT COUNT(*) AS total_mahasiswa FROM mahasiswa;
SELECT COUNT(*) AS total_absensi FROM absensi;
SELECT COUNT(*) AS total_pengajuan_izin FROM pengajuan_izin;
