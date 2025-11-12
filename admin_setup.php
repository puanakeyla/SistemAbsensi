<?php
/**
 * Script setup untuk membuat akun admin pertama kali
 * 
 * Cara pakai:
 * 1. Buka browser: http://localhost/SistemAbsensi/admin_setup.php
 * 2. Isi form untuk membuat akun admin baru
 * 3. Setelah berhasil, HAPUS file ini (admin_setup.php) untuk keamanan
 * 
 * Atau jalankan langsung tanpa form dengan query SQL manual di phpMyAdmin
 */

require 'config/db.php';

$message = '';
$success = false;

// Check jika ada request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validasi
    if (empty($username) || empty($nama) || empty($email) || empty($password)) {
        $message = 'Semua field harus diisi.';
    } elseif ($password !== $password_confirm) {
        $message = 'Password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $message = 'Password minimal 6 karakter.';
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert ke DB
        $stmt = $conn->prepare('INSERT INTO admin (username, nama, email, password, created_at) VALUES (?, ?, ?, ?, NOW())');
        if ($stmt) {
            $stmt->bind_param('ssss', $username, $nama, $email, $password_hash);
            if ($stmt->execute()) {
                $success = true;
                $message = 'Akun admin berhasil dibuat! Silakan login di <a href="public/admin/login.php">login page</a>.';
            } else {
                $message = 'Error: ' . $stmt->error;
            }
        } else {
            $message = 'Error prepare: ' . $conn->error;
        }
    }
}

// Check apakah sudah ada admin
$adminCount = 0;
$res = $conn->query('SELECT COUNT(*) as cnt FROM admin WHERE deleted_at IS NULL');
if ($res) {
    $row = $res->fetch_assoc();
    $adminCount = $row['cnt'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Admin - Sistem Absensi</title>
    <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, Helvetica, sans-serif; background: linear-gradient(135deg, #062a4a, #0b6fa6); color: #222; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
    .container { background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 40px rgba(10, 24, 40, 0.15); width: 100%; max-width: 500px; }
    h1 { color: #062a4a; margin-bottom: 10px; }
    .info { background: #d1ecf1; color: #0c5460; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; }
    .warning { background: #fff3cd; color: #856404; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; }
    .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
    .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
    .form-group { margin-bottom: 16px; }
    label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; }
    input[type=text], input[type=email], input[type=password] { width: 100%; padding: 12px; border: 1px solid #d7e7f3; border-radius: 8px; font-size: 1rem; }
    input:focus { outline: none; border-color: #0b6fa6; box-shadow: 0 0 0 3px rgba(11, 111, 166, 0.1); }
    .btn { background: linear-gradient(90deg, #062a4a, #0b6fa6); color: #fff; padding: 12px; border-radius: 8px; border: none; cursor: pointer; width: 100%; font-weight: 600; font-size: 1rem; margin-top: 8px; }
    .btn:hover { opacity: 0.95; }
    .btn:disabled { opacity: 0.6; cursor: not-allowed; }
    a { color: #0b6fa6; text-decoration: none; }
    a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Setup Admin Account</h1>
        
        <?php if ($adminCount > 0): ?>
            <div class="info">
                Sudah ada <?php echo $adminCount; ?> akun admin di database. Anda bisa <a href="public/admin/login.php">login langsung</a> atau membuat akun admin tambahan di bawah ini.
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">✓ <?php echo $message; ?></div>
        <?php elseif (!empty($message)): ?>
            <div class="error">✗ <?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password (min. 6 karakter)</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>

            <button type="submit" class="btn">Buat Akun Admin</button>
        </form>

        <div class="warning" style="margin-top: 20px;">
            <strong>⚠ Penting:</strong> Setelah selesai membuat akun admin, HAPUS file <code>admin_setup.php</code> ini untuk keamanan.
        </div>
    </div>
</body>
</html>
