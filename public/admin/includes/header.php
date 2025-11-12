<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// Middleware: pastikan sudah login
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Helper untuk mendapatkan nama admin
function admin_name() {
    return $_SESSION['admin_name'] ?? 'Admin';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Sistem Absensi</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-wrap">
        <aside class="sidebar">
            <div class="brand">
                <div class="logo">SA</div>
                <div>
                    <div style="font-weight:700;color:var(--primary)">Sistem Absensi</div>
                    <div class="muted" style="font-size:0.85rem">Admin Panel</div>
                </div>
            </div>
            <nav>
                <ul class="nav">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="students.php">Mahasiswa</a></li>
                    <li><a href="attendance.php">Absensi</a></li>
                    <li><a href="izin_requests.php">Pengajuan Izin</a></li>
                    <li><a href="courses.php">Mata Kuliah</a></li>
                    <li><a href="classes.php">Kelas</a></li>
                    <li><a href="schedules.php">Jadwal</a></li>
                    <li><a href="admins.php">Admins</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main">
            <div class="topbar">
                <div style="font-weight:700">Dashboard Admin</div>
                <div class="right">
                    <div class="muted">Halo, <?php echo htmlspecialchars(admin_name()); ?></div>
                </div>
            </div>
            <div style="padding:18px"> 
