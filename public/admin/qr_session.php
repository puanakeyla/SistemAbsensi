<?php
require_once __DIR__ . '/includes/header.php';

// Handle buka session baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buka_session'])) {
    $id_jadwal = (int)$_POST['id_jadwal'];
    $tanggal = $_POST['tanggal'];
    $qr_code = 'QR' . time() . rand(100, 999);
    $admin_id = $_SESSION['admin_id'];
    
    // Check if session already exists
    $check = $conn->query("SELECT id FROM absensi_session WHERE id_jadwal = $id_jadwal AND tanggal = '$tanggal' AND status = 'aktif'");
    
    if ($check->num_rows > 0) {
        $msg = 'exists';
    } else {
        $sql = "INSERT INTO absensi_session (id_jadwal, tanggal, qr_code, dibuka_oleh) 
                VALUES ($id_jadwal, '$tanggal', '$qr_code', $admin_id)";
        if ($conn->query($sql)) {
            $msg = 'opened';
        }
    }
    header("Location: qr_session.php?msg=$msg");
    exit;
}

// Handle tutup session
if (isset($_GET['close']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE absensi_session SET status = 'selesai', waktu_tutup = NOW() WHERE id = $id");
    header('Location: qr_session.php?msg=closed');
    exit;
}

// Get active sessions
$active_sessions = $conn->query("
    SELECT 
        ases.*, 
        mk.kode_mk, 
        mk.nama_mk,
        k.nama_kelas,
        j.hari,
        j.jam_mulai,
        j.jam_selesai,
        j.ruangan,
        a.nama as admin_nama,
        COUNT(ab.id) as total_hadir
    FROM absensi_session ases
    JOIN jadwal j ON ases.id_jadwal = j.id
    JOIN kelas k ON j.id_kelas = k.id
    JOIN mata_kuliah mk ON k.id_mk = mk.id
    JOIN admin a ON ases.dibuka_oleh = a.id
    LEFT JOIN absensi ab ON ases.id = ab.id_session
    WHERE ases.status = 'aktif'
    GROUP BY ases.id
    ORDER BY ases.waktu_buka DESC
");

// Get jadwal untuk hari ini
$today = date('l');
$hari_indo = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa', 
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];
$hari = $hari_indo[$today] ?? 'Senin';

$jadwal_today = $conn->query("
    SELECT 
        j.id,
        mk.kode_mk,
        mk.nama_mk,
        k.nama_kelas,
        j.hari,
        j.jam_mulai,
        j.jam_selesai,
        j.ruangan
    FROM jadwal j
    JOIN kelas k ON j.id_kelas = k.id
    JOIN mata_kuliah mk ON k.id_mk = mk.id
    WHERE j.hari = '$hari' AND j.deleted_at IS NULL
    ORDER BY j.jam_mulai
");

?>

<h1><i class="fas fa-qrcode"></i> QR Code Absensi</h1>

<?php if (isset($_GET['msg'])): ?>
<div class="alert" style="background: <?php echo $_GET['msg'] == 'exists' ? '#fff3cd' : '#d4edda'; ?>; color: <?php echo $_GET['msg'] == 'exists' ? '#856404' : '#155724'; ?>; padding: 12px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid <?php echo $_GET['msg'] == 'exists' ? '#ffc107' : '#28a745'; ?>;">
    <?php 
    if ($_GET['msg'] == 'opened') echo '✓ Session absensi berhasil dibuka!';
    if ($_GET['msg'] == 'exists') echo '⚠ Session untuk jadwal ini sudah aktif!';
    if ($_GET['msg'] == 'closed') echo '✓ Session berhasil ditutup!';
    ?>
</div>
<?php endif; ?>

<!-- Active Sessions -->
<div class="card" style="margin-bottom: 20px;">
    <h2><i class="fas fa-signal"></i> Session Aktif</h2>
    
    <?php if ($active_sessions && $active_sessions->num_rows > 0): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-top: 16px;">
        <?php while ($session = $active_sessions->fetch_assoc()): ?>
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 20px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                <div>
                    <h3 style="margin: 0 0 8px 0; font-size: 1.1rem;"><?php echo htmlspecialchars($session['nama_mk']); ?></h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">Kelas <?php echo htmlspecialchars($session['nama_kelas']); ?> • <?php echo htmlspecialchars($session['ruangan']); ?></p>
                </div>
                <span style="background: rgba(255,255,255,0.3); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">
                    <i class="fas fa-circle" style="color: #4ade80; font-size: 0.6rem;"></i> Aktif
                </span>
            </div>
            
            <!-- QR Code Display -->
            <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; margin: 16px 0;">
                <div style="font-size: 3rem; color: #333;">
                    <i class="fas fa-qrcode"></i>
                </div>
                <p style="margin: 12px 0 4px 0; font-weight: 700; color: #333; font-size: 1.1rem; letter-spacing: 2px;">
                    <?php echo htmlspecialchars($session['qr_code']); ?>
                </p>
                <p style="margin: 0; font-size: 0.8rem; color: #666;">Scan untuk absen</p>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700;"><?php echo $session['total_hadir']; ?></div>
                    <div style="font-size: 0.85rem; opacity: 0.9;">Sudah Absen</div>
                </div>
                <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 1rem; font-weight: 600;"><?php echo date('H:i', strtotime($session['waktu_buka'])); ?></div>
                    <div style="font-size: 0.85rem; opacity: 0.9;">Dibuka</div>
                </div>
            </div>
            
            <div style="display: flex; gap: 8px;">
                <a href="qr_scan_page.php?code=<?php echo $session['qr_code']; ?>" target="_blank" class="btn" style="flex: 1; background: rgba(255,255,255,0.3); color: white; text-align: center; padding: 10px; font-size: 0.85rem;">
                    <i class="fas fa-external-link-alt"></i> Lihat Halaman Scan
                </a>
                <a href="qr_session.php?close=1&id=<?php echo $session['id']; ?>" onclick="return confirm('Tutup session absensi ini?')" class="btn" style="background: #ef4444; color: white; padding: 10px; font-size: 0.85rem;">
                    <i class="fas fa-times-circle"></i> Tutup
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div style="text-align: center; padding: 40px; color: var(--gray);">
        <i class="fas fa-info-circle" style="font-size: 3rem; opacity: 0.3; margin-bottom: 16px;"></i>
        <p style="margin: 0; font-size: 1.1rem;">Belum ada session aktif</p>
        <p style="margin: 8px 0 0 0; font-size: 0.9rem;">Buka session baru di bawah ini</p>
    </div>
    <?php endif; ?>
</div>

<!-- Buka Session Baru -->
<div class="card">
    <h2><i class="fas fa-plus-circle"></i> Buka Session Baru</h2>
    <p style="color: var(--gray); margin-bottom: 16px;">Pilih jadwal untuk hari ini (<?php echo $hari; ?>)</p>
    
    <?php if ($jadwal_today && $jadwal_today->num_rows > 0): ?>
    <form method="POST">
        <input type="hidden" name="buka_session" value="1">
        <input type="hidden" name="tanggal" value="<?php echo date('Y-m-d'); ?>">
        
        <div style="display: grid; gap: 12px;">
            <?php while ($jadwal = $jadwal_today->fetch_assoc()): ?>
            <div style="border: 2px solid #e5e7eb; border-radius: 8px; padding: 16px; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.borderColor='var(--primary)'; this.style.background='#f8fafc';" onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='white';">
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 8px 0; color: var(--primary);">
                        <?php echo htmlspecialchars($jadwal['kode_mk']); ?> - <?php echo htmlspecialchars($jadwal['nama_mk']); ?>
                    </h3>
                    <p style="margin: 0; color: var(--gray); font-size: 0.9rem;">
                        <i class="fas fa-door-open"></i> Kelas <?php echo htmlspecialchars($jadwal['nama_kelas']); ?> • 
                        <i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($jadwal['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($jadwal['jam_selesai'])); ?> • 
                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($jadwal['ruangan']); ?>
                    </p>
                </div>
                <button type="submit" name="id_jadwal" value="<?php echo $jadwal['id']; ?>" class="btn" style="background: var(--success);">
                    <i class="fas fa-qrcode"></i> Buka Session
                </button>
            </div>
            <?php endwhile; ?>
        </div>
    </form>
    <?php else: ?>
    <div style="text-align: center; padding: 30px; background: #f8fafc; border-radius: 8px; color: var(--gray);">
        <i class="fas fa-calendar-times" style="font-size: 2.5rem; opacity: 0.3; margin-bottom: 12px;"></i>
        <p style="margin: 0; font-size: 1rem;">Tidak ada jadwal untuk hari ini (<?php echo $hari; ?>)</p>
    </div>
    <?php endif; ?>
</div>

<div style="margin-top: 20px; text-align: center;">
    <a href="index.php" class="btn" style="background: var(--gray);">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
