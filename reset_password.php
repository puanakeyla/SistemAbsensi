<?php
require_once 'config/db.php';

$username = 'cinsy';
$new_password = '12345';

// Hash password dengan password_hash (sama seperti di sistem)
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password di database
$stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $hashed_password, $username);

if ($stmt->execute()) {
    echo "✅ SUCCESS! Password berhasil diupdate!\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "LOGIN CREDENTIALS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Username: cinsy\n";
    echo "Password: 12345\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "🌐 Silakan login di:\n";
    echo "http://localhost/SistemAbsensi/public/admin/login.php\n\n";
} else {
    echo "❌ ERROR: Gagal update password!\n";
    echo "Error: " . $stmt->error . "\n";
}

$stmt->close();
$conn->close();
?>
