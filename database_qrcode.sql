-- ========================================
-- Tambah tabel untuk QR Code & Session Absensi
-- ========================================
USE absensi_kampus;

-- Tabel untuk session absensi (QR Code aktif)
CREATE TABLE IF NOT EXISTS absensi_session (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_jadwal INT NOT NULL,
    tanggal DATE NOT NULL,
    waktu_buka TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    waktu_tutup TIMESTAMP NULL,
    qr_code VARCHAR(100) UNIQUE NOT NULL, -- Kode unik untuk QR
    status ENUM('aktif','selesai') DEFAULT 'aktif',
    dibuka_oleh INT NOT NULL, -- id admin yang buka sesi
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id) ON DELETE CASCADE,
    FOREIGN KEY (dibuka_oleh) REFERENCES admin(id),
    UNIQUE KEY unique_session (id_jadwal, tanggal),
    INDEX idx_qr_code (qr_code),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Update tabel absensi untuk track dari QR Code
ALTER TABLE absensi 
ADD COLUMN IF NOT EXISTS id_session INT AFTER id_jadwal,
ADD COLUMN IF NOT EXISTS scan_method ENUM('qr','manual','face') DEFAULT 'qr' AFTER device_info,
ADD FOREIGN KEY (id_session) REFERENCES absensi_session(id) ON DELETE SET NULL;

-- Insert sample session untuk testing (hari ini)
INSERT INTO absensi_session (id_jadwal, tanggal, qr_code, dibuka_oleh, status) VALUES
(1, CURDATE(), CONCAT('QR', UNIX_TIMESTAMP(), FLOOR(RAND() * 1000)), 1, 'aktif'),
(2, CURDATE(), CONCAT('QR', UNIX_TIMESTAMP() + 1, FLOOR(RAND() * 1000)), 1, 'aktif');

SELECT 'Tabel absensi_session berhasil dibuat!' AS Status;
SELECT * FROM absensi_session;
