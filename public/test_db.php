<?php
require_once __DIR__ . '/../config/db.php';

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    echo "Koneksi gagal. Periksa konfigurasi di config/db.php dan pastikan MySQL berjalan serta database sudah di-import.";
    exit;
}

try {
    $stmt = $conn->query('SELECT COUNT(*) AS total FROM mahasiswa');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $row ? $row['total'] : 0;
    echo "Koneksi berhasil. Jumlah baris pada tabel `mahasiswa`: " . $total;
} catch (PDOException $e) {
    echo "Query error: " . $e->getMessage();
}
