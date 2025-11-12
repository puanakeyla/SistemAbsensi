<?php
session_start();
require __DIR__ . '/includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Masukkan username dan password.';
    } else {
        $stmt = $conn->prepare('SELECT id, username, password, nama FROM admin WHERE username = ? AND deleted_at IS NULL');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['nama'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        } else {
            // Fallback dev: jika tabel admin kosong
            $checkAll = $conn->query('SELECT COUNT(*) AS cnt FROM admin');
            $cnt = 0;
            if ($checkAll) { $r = $checkAll->fetch_assoc(); $cnt = (int)$r['cnt']; }
            if ($cnt === 0 && $username === 'admin' && $password === 'admin123') {
                $_SESSION['admin_id'] = 0;
                $_SESSION['admin_name'] = 'Administrator (fallback)';
                header('Location: index.php');
                exit;
            }
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login - Sistem Absensi</title>
    <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, Helvetica, sans-serif; background: linear-gradient(135deg, #062a4a, #0b6fa6); color: #222; display: flex; align-items: center; justify-content: center; height: 100vh; }
    .card { background: #fff; padding: 32px; border-radius: 12px; box-shadow: 0 10px 40px rgba(10, 24, 40, 0.15); width: 100%; max-width: 400px; }
    .card h2 { margin-bottom: 20px; color: #062a4a; font-size: 1.8rem; }
    .form-group { margin-bottom: 16px; }
    label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; }
    input[type=text], input[type=password] { width: 100%; padding: 12px; border: 1px solid #d7e7f3; border-radius: 8px; font-size: 1rem; }
    input[type=text]:focus, input[type=password]:focus { outline: none; border-color: #0b6fa6; box-shadow: 0 0 0 3px rgba(11, 111, 166, 0.1); }
    .btn { background: linear-gradient(90deg, #062a4a, #0b6fa6); color: #fff; padding: 12px; border-radius: 8px; border: none; cursor: pointer; width: 100%; font-weight: 600; font-size: 1rem; margin-top: 8px; }
    .btn:hover { opacity: 0.95; }
    .error { color: #e74c3c; margin-bottom: 16px; padding: 10px; background: #fadbd8; border-radius: 6px; }
    .info { margin-top: 16px; font-size: 0.9rem; color: #6b7280; text-align: center; }
    .info strong { color: #062a4a; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login Admin</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button class="btn" type="submit">Masuk</button>
        </form>
        <div class="info">
            Jika belum ada akun admin, gunakan <strong>admin / admin123</strong> (development only).
        </div>
    </div>
</body>
</html>
