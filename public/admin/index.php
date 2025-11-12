<?php
require_once __DIR__ . '/includes/header.php';

// Basic stats (conn sudah tersedia dari header.php via db.php)
$total_mahasiswa = 0;
$res = $conn->query('SELECT COUNT(*) AS cnt FROM mahasiswa WHERE deleted_at IS NULL');
if ($res) { $row = $res->fetch_assoc(); $total_mahasiswa = (int)$row['cnt']; }

$total_kelas = 0;
$res = $conn->query('SELECT COUNT(*) AS cnt FROM kelas WHERE deleted_at IS NULL');
if ($res) { $row = $res->fetch_assoc(); $total_kelas = (int)$row['cnt']; }

$pending_izin = 0;
$res = $conn->query("SELECT COUNT(*) AS cnt FROM pengajuan_izin WHERE status = 'pending'");
if ($res) { $row = $res->fetch_assoc(); $pending_izin = (int)$row['cnt']; }

$total_absensi = 0;
$res = $conn->query("SELECT COUNT(*) AS cnt FROM absensi WHERE deleted_at IS NULL");
if ($res) { $row = $res->fetch_assoc(); $total_absensi = (int)$row['cnt']; }

?>

            <h1><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>

            <!-- Hero Banner -->
            <div class="hero">
                <div class="logo">SA</div>
                <div>
                    <h2>Selamat datang di Admin Panel</h2>
                    <p>Kelola mahasiswa, absensi, pengajuan izin, dan jadwal akademik dengan mudah di bawah ini.</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="cards">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: var(--secondary);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.8rem; color: var(--dark); margin: 0;"><?php echo $total_mahasiswa; ?></h3>
                        <p style="color: var(--gray); margin: 0;">Total Mahasiswa</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: var(--success);">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.8rem; color: var(--dark); margin: 0;"><?php echo $total_kelas; ?></h3>
                        <p style="color: var(--gray); margin: 0;">Total Kelas</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: var(--warning);">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.8rem; color: var(--dark); margin: 0;"><?php echo $pending_izin; ?></h3>
                        <p style="color: var(--gray); margin: 0;">Pengajuan Izin (Pending)</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: var(--danger);">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.8rem; color: var(--dark); margin: 0;"><?php echo $total_absensi; ?></h3>
                        <p style="color: var(--gray); margin: 0;">Total Absensi</p>
                    </div>
                </div>
            </div>

            <!-- Quick Links Section -->
            <div class="card">
                <h2><i class="fas fa-link"></i> Quick Links</h2>
                <p style="color: var(--gray); margin-bottom: 16px;">Akses halaman manajemen dengan cepat dari sini.</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                    <a href="students.php" class="btn"><i class="fas fa-users"></i> Manajemen Mahasiswa</a>
                    <a href="attendance.php" class="btn"><i class="fas fa-clipboard-check"></i> Rekap Absensi</a>
                    <a href="izin_requests.php" class="btn"><i class="fas fa-file-medical"></i> Pengajuan Izin</a>
                    <a href="courses.php" class="btn"><i class="fas fa-book"></i> Mata Kuliah</a>
                    <a href="classes.php" class="btn"><i class="fas fa-chalkboard"></i> Kelas</a>
                    <a href="schedules.php" class="btn"><i class="fas fa-calendar"></i> Jadwal</a>
                    <a href="admins.php" class="btn"><i class="fas fa-user-tie"></i> Manajemen Admin</a>
                </div>
            </div>

<?php
require_once __DIR__ . '/includes/footer.php';
