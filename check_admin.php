<?php
require_once 'config/db.php';

echo "=== DATA ADMIN DI DATABASE ===\n\n";

$result = $conn->query('SELECT id, username, nama, created_at FROM admin');

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . "\n";
        echo "Username: " . $row['username'] . "\n";
        echo "Nama: " . $row['nama'] . "\n";
        echo "Created: " . $row['created_at'] . "\n";
        echo "----------------------------\n";
    }
} else {
    echo "❌ Tidak ada admin di database!\n";
    echo "Silakan buat admin baru di:\n";
    echo "http://localhost/SistemAbsensi/admin_setup.php\n";
}

echo "\n💡 CATATAN:\n";
echo "Password tidak ditampilkan karena sudah di-hash (aman).\n";
echo "Jika lupa password, buat admin baru atau reset via database.\n";
?>
