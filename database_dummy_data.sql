-- ========================================
-- DATA DUMMY untuk Testing
-- ========================================
USE absensi_kampus;

-- ==========================
-- 1. Data Admin
-- ==========================
INSERT INTO admin (username, nama, email, password) VALUES
('cinsy', 'cindy', 'cindy@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: 12345
('admin', 'Administrator', 'admin@kampus.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: 12345

-- ==========================
-- 2. Data Mahasiswa (10 mahasiswa)
-- ==========================
INSERT INTO mahasiswa (nim, nama, email, password) VALUES
('2021001', 'Andi Pratama', 'andi.pratama@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021002', 'Budi Santoso', 'budi.santoso@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021003', 'Citra Dewi', 'citra.dewi@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021004', 'Dina Marlina', 'dina.marlina@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021005', 'Eko Widodo', 'eko.widodo@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021006', 'Fitri Handayani', 'fitri.handayani@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021007', 'Gita Permata', 'gita.permata@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021008', 'Hendra Wijaya', 'hendra.wijaya@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021009', 'Indah Sari', 'indah.sari@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('2021010', 'Joko Susilo', 'joko.susilo@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ==========================
-- 3. Data Mata Kuliah (8 mata kuliah)
-- ==========================
INSERT INTO mata_kuliah (kode_mk, nama_mk, sks) VALUES
('IF101', 'Pemrograman Web', 3),
('IF102', 'Basis Data', 3),
('IF103', 'Algoritma dan Struktur Data', 4),
('IF104', 'Jaringan Komputer', 3),
('IF105', 'Sistem Operasi', 3),
('IF106', 'Rekayasa Perangkat Lunak', 3),
('IF107', 'Kecerdasan Buatan', 3),
('IF108', 'Pemrograman Mobile', 3);

-- ==========================
-- 4. Data Kelas (8 kelas, 1 per mata kuliah)
-- ==========================
INSERT INTO kelas (nama_kelas, id_mk) VALUES
('A', 1), -- Pemrograman Web A
('A', 2), -- Basis Data A
('B', 3), -- Algoritma B
('A', 4), -- Jaringan Komputer A
('B', 5), -- Sistem Operasi B
('A', 6), -- RPL A
('A', 7), -- AI A
('B', 8); -- Mobile B

-- ==========================
-- 5. Data Jadwal (8 jadwal, masing-masing kelas punya 1 jadwal)
-- ==========================
INSERT INTO jadwal (id_kelas, hari, jam_mulai, jam_selesai, lokasi_lat, lokasi_long, radius_meter) VALUES
(1, 'Senin', '08:00:00', '10:30:00', -6.200000, 106.816666, 50),    -- Pemrograman Web
(2, 'Senin', '13:00:00', '15:30:00', -6.200000, 106.816666, 50),    -- Basis Data
(3, 'Selasa', '08:00:00', '11:00:00', -6.200000, 106.816666, 50),   -- Algoritma
(4, 'Rabu', '08:00:00', '10:30:00', -6.200000, 106.816666, 50),     -- Jaringan
(5, 'Rabu', '13:00:00', '15:30:00', -6.200000, 106.816666, 50),     -- Sistem Operasi
(6, 'Kamis', '08:00:00', '10:30:00', -6.200000, 106.816666, 50),    -- RPL
(7, 'Jumat', '08:00:00', '10:30:00', -6.200000, 106.816666, 50),    -- AI
(8, 'Jumat', '13:00:00', '15:30:00', -6.200000, 106.816666, 50);    -- Mobile

-- ==========================
-- 6. Relasi Mahasiswa-Kelas (setiap mahasiswa ambil 4-5 mata kuliah)
-- ==========================
-- Mahasiswa 2021001 (Andi)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(1, 1), (1, 2), (1, 3), (1, 4);

-- Mahasiswa 2021002 (Budi)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(2, 1), (2, 2), (2, 5), (2, 6);

-- Mahasiswa 2021003 (Citra)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(3, 2), (3, 3), (3, 4), (3, 7);

-- Mahasiswa 2021004 (Dina)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(4, 1), (4, 3), (4, 6), (4, 8);

-- Mahasiswa 2021005 (Eko)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(5, 2), (5, 4), (5, 5), (5, 7);

-- Mahasiswa 2021006 (Fitri)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(6, 1), (6, 3), (6, 6), (6, 8);

-- Mahasiswa 2021007 (Gita)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(7, 2), (7, 4), (7, 5), (7, 7);

-- Mahasiswa 2021008 (Hendra)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(8, 1), (8, 3), (8, 6), (8, 8);

-- Mahasiswa 2021009 (Indah)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(9, 2), (9, 4), (9, 5), (9, 7);

-- Mahasiswa 2021010 (Joko)
INSERT INTO mahasiswa_kelas (id_mahasiswa, id_kelas) VALUES
(10, 1), (10, 3), (10, 6), (10, 8);

-- ==========================
-- 7. Data Absensi (30+ records untuk testing)
-- ==========================
-- Minggu 1 (2025-11-18 - Senin)
INSERT INTO absensi (id_mahasiswa, id_jadwal, tanggal, status, lokasi_lat, lokasi_long, verified) VALUES
-- Senin - Pemrograman Web (08:00)
(1, 1, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),
(2, 1, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),
(4, 1, '2025-11-18', 'Izin', NULL, NULL, TRUE),
(6, 1, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),
(8, 1, '2025-11-18', 'Alpha', NULL, NULL, FALSE),
(10, 1, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),

-- Senin - Basis Data (13:00)
(1, 2, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),
(2, 2, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),
(3, 2, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),
(5, 2, '2025-11-18', 'Sakit', NULL, NULL, TRUE),
(7, 2, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),
(9, 2, '2025-11-18', 'Hadir', -6.200000, 106.816666, TRUE),

-- Selasa - Algoritma (08:00)
(1, 3, '2025-11-19', 'Hadir', -6.200000, 106.816666, TRUE),
(3, 3, '2025-11-19', 'Hadir', -6.200000, 106.816666, TRUE),
(4, 3, '2025-11-19', 'Hadir', -6.200000, 106.816666, TRUE),
(6, 3, '2025-11-19', 'Alpha', NULL, NULL, FALSE),
(8, 3, '2025-11-19', 'Hadir', -6.200000, 106.816666, TRUE),
(10, 3, '2025-11-19', 'Hadir', -6.200000, 106.816666, TRUE),

-- Rabu - Jaringan Komputer (08:00)
(1, 4, '2025-11-20', 'Hadir', -6.200000, 106.816666, TRUE),
(3, 4, '2025-11-20', 'Hadir', -6.200000, 106.816666, TRUE),
(5, 4, '2025-11-20', 'Hadir', -6.200000, 106.816666, TRUE),
(7, 4, '2025-11-20', 'Izin', NULL, NULL, TRUE),
(9, 4, '2025-11-20', 'Hadir', -6.200000, 106.816666, TRUE);

-- ==========================
-- 8. Data Pengajuan Izin (5 records)
-- ==========================
INSERT INTO pengajuan_izin (id_mahasiswa, id_jadwal, tanggal, alasan, status, verified_by, verified_at) VALUES
(4, 1, '2025-11-18', 'Sakit demam, ada surat dokter', 'disetujui', 1, '2025-11-18 12:00:00'),
(5, 2, '2025-11-18', 'Sakit flu', 'disetujui', 1, '2025-11-18 14:00:00'),
(7, 4, '2025-11-20', 'Ada keperluan keluarga', 'pending', NULL, NULL),
(6, 3, '2025-11-19', 'Terlambat bangun', 'ditolak', 1, '2025-11-19 10:00:00'),
(8, 1, '2025-11-18', 'Lupa absen', 'pending', NULL, NULL);

-- ==========================
-- 9. Data Notifikasi (5 records)
-- ==========================
INSERT INTO notifikasi (id_mahasiswa, type, message, is_read) VALUES
(1, 'absensi_dibuka', 'Absensi Pemrograman Web telah dibuka', TRUE),
(2, 'absensi_dibuka', 'Absensi Basis Data telah dibuka', TRUE),
(4, 'izin_disetujui', 'Pengajuan izin Anda telah disetujui', FALSE),
(5, 'izin_disetujui', 'Pengajuan izin Anda telah disetujui', FALSE),
(6, 'izin_ditolak', 'Pengajuan izin Anda ditolak. Alasan tidak valid.', FALSE);

-- ==========================
-- Summary
-- ==========================
SELECT 'Data dummy berhasil diinsert!' AS Status;
SELECT COUNT(*) AS total_mahasiswa FROM mahasiswa;
SELECT COUNT(*) AS total_admin FROM admin;
SELECT COUNT(*) AS total_mata_kuliah FROM mata_kuliah;
SELECT COUNT(*) AS total_kelas FROM kelas;
SELECT COUNT(*) AS total_jadwal FROM jadwal;
SELECT COUNT(*) AS total_absensi FROM absensi;
SELECT COUNT(*) AS total_pengajuan_izin FROM pengajuan_izin;
SELECT COUNT(*) AS total_notifikasi FROM notifikasi;
