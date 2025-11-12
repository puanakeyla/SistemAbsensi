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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sistem Absensi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <!-- Topbar -->
    <div class="topbar">
        <div class="brand">
            <div class="logo">SA</div>
            <div class="campus-name">Universitas BDL — Admin Panel</div>
        </div>
        <div style="margin-left: auto; display: flex; align-items: center; gap: 12px;">
            <span style="color: rgba(255,255,255,0.8);">Halo, <?php echo htmlspecialchars(admin_name()); ?></span>
        </div>
    </div>

    <div class="admin-wrap">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-shield-alt"></i> Admin Panel</h2>
                <p>Sistem Absensi</p>
            </div>
            <nav>
                <ul class="nav">
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> <span class="label">Dashboard</span></a></li>
                    <li><a href="students.php"><i class="fas fa-users"></i> <span class="label">Mahasiswa</span></a></li>
                    <li><a href="attendance.php"><i class="fas fa-clipboard-check"></i> <span class="label">Absensi</span></a></li>
                    <li><a href="izin_requests.php"><i class="fas fa-file-medical"></i> <span class="label">Pengajuan Izin</span></a></li>
                    <li><a href="courses.php"><i class="fas fa-book"></i> <span class="label">Mata Kuliah</span></a></li>
                    <li><a href="classes.php"><i class="fas fa-chalkboard"></i> <span class="label">Kelas</span></a></li>
                    <li><a href="schedules.php"><i class="fas fa-calendar"></i> <span class="label">Jadwal</span></a></li>
                    <li><a href="admins.php"><i class="fas fa-user-tie"></i> <span class="label">Admins</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span class="label">Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main">
 
