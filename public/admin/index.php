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

?>

            <h1>Dashboard</h1>
            <div class="cards">
                <div class="card">
                    <h3>Total Mahasiswa</h3>
                    <p style="font-size:28px;font-weight:700"><?php echo $total_mahasiswa; ?></p>
                </div>
                <div class="card">
                    <h3>Total Kelas</h3>
                    <p style="font-size:28px;font-weight:700"><?php echo $total_kelas; ?></p>
                </div>
                <div class="card">
                    <h3>Pengajuan Izin (Pending)</h3>
                    <p style="font-size:28px;font-weight:700"><?php echo $pending_izin; ?></p>
                </div>
            </div>

            <section class="card">
                <h2>Quick Links</h2>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <a class="btn" href="students.php">Mahasiswa</a>
                    <a class="btn" href="attendance.php">Absensi</a>
                    <a class="btn" href="izin_requests.php">Pengajuan Izin</a>
                    <a class="btn" href="courses.php">Mata Kuliah</a>
                    <a class="btn" href="classes.php">Kelas</a>
                    <a class="btn" href="schedules.php">Jadwal</a>
                </div>
            </section>

<?php
require_once __DIR__ . '/includes/footer.php';
