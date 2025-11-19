<?php
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = $conn->query("SELECT * FROM mahasiswa WHERE id = $id AND deleted_at IS NULL");
$mahasiswa = $result->fetch_assoc();

if (!$mahasiswa) {
    header('Location: students.php');
    exit;
}

// Get kelas yang diambil
$kelas_result = $conn->query("
    SELECT mk.kode_mk, mk.nama_mk, k.nama_kelas, mk.sks, k.dosen_pengampu
    FROM mahasiswa_kelas mkls
    JOIN kelas k ON mkls.id_kelas = k.id
    JOIN mata_kuliah mk ON k.id_mk = mk.id
    WHERE mkls.id_mahasiswa = $id AND mkls.deleted_at IS NULL
    ORDER BY mk.kode_mk
");

// Get statistik absensi
$stats = $conn->query("
    SELECT 
        COUNT(CASE WHEN status = 'Hadir' THEN 1 END) as hadir,
        COUNT(CASE WHEN status = 'Izin' THEN 1 END) as izin,
        COUNT(CASE WHEN status = 'Sakit' THEN 1 END) as sakit,
        COUNT(CASE WHEN status = 'Alpha' THEN 1 END) as alpha,
        COUNT(*) as total
    FROM absensi
    WHERE id_mahasiswa = $id AND deleted_at IS NULL
")->fetch_assoc();

?>

<h1><i class="fas fa-user-circle"></i> Detail Mahasiswa</h1>

<div class="card" style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2><i class="fas fa-id-card"></i> Informasi Pribadi</h2>
        <div>
            <a href="student_form.php?id=<?php echo $id; ?>" class="btn" style="background: var(--warning); font-size: 0.85rem;">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="students.php" class="btn" style="font-size: 0.85rem;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <div>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 10px 0; font-weight: 600; width: 150px; color: var(--dark);"><i class="fas fa-id-card"></i> NIM</td>
                    <td style="padding: 10px 0;">: <?php echo htmlspecialchars($mahasiswa['nim']); ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-weight: 600; color: var(--dark);"><i class="fas fa-user"></i> Nama</td>
                    <td style="padding: 10px 0;">: <?php echo htmlspecialchars($mahasiswa['nama']); ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-weight: 600; color: var(--dark);"><i class="fas fa-envelope"></i> Email</td>
                    <td style="padding: 10px 0;">: <?php echo htmlspecialchars($mahasiswa['email']); ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-weight: 600; color: var(--dark);"><i class="fas fa-phone"></i> Telepon</td>
                    <td style="padding: 10px 0;">: <?php echo htmlspecialchars($mahasiswa['no_telp'] ?? '-'); ?></td>
                </tr>
            </table>
        </div>
        
        <div>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 10px 0; font-weight: 600; width: 150px; color: var(--dark);"><i class="fas fa-calendar"></i> Tgl Lahir</td>
                    <td style="padding: 10px 0;">: <?php echo $mahasiswa['tanggal_lahir'] ? date('d F Y', strtotime($mahasiswa['tanggal_lahir'])) : '-'; ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-weight: 600; color: var(--dark);"><i class="fas fa-venus-mars"></i> Jenis Kelamin</td>
                    <td style="padding: 10px 0;">: <?php echo $mahasiswa['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-weight: 600; color: var(--dark);"><i class="fas fa-toggle-on"></i> Status</td>
                    <td style="padding: 10px 0;">: 
                        <span class="status-badge <?php echo $mahasiswa['status'] == 'aktif' ? 'status-success' : 'status-warning'; ?>">
                            <?php echo ucfirst($mahasiswa['status']); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-weight: 600; color: var(--dark);"><i class="fas fa-clock"></i> Terdaftar</td>
                    <td style="padding: 10px 0;">: <?php echo date('d F Y', strtotime($mahasiswa['created_at'])); ?></td>
                </tr>
            </table>
        </div>
    </div>
    
    <?php if ($mahasiswa['alamat']): ?>
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
        <p style="font-weight: 600; margin-bottom: 8px; color: var(--dark);"><i class="fas fa-map-marker-alt"></i> Alamat:</p>
        <p style="color: var(--gray);"><?php echo nl2br(htmlspecialchars($mahasiswa['alamat'])); ?></p>
    </div>
    <?php endif; ?>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px;">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;">Total Pertemuan</div>
        <div style="font-size: 2rem; font-weight: 700;"><?php echo $stats['total']; ?></div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;">Hadir</div>
        <div style="font-size: 2rem; font-weight: 700;"><?php echo $stats['hadir']; ?></div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;">Izin/Sakit</div>
        <div style="font-size: 2rem; font-weight: 700;"><?php echo ($stats['izin'] + $stats['sakit']); ?></div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;">Alpha</div>
        <div style="font-size: 2rem; font-weight: 700;"><?php echo $stats['alpha']; ?></div>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 16px;"><i class="fas fa-book"></i> Mata Kuliah yang Diambil</h2>
    
    <?php if ($kelas_result && $kelas_result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 100px;">Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th style="width: 80px;">Kelas</th>
                <th style="width: 80px;">SKS</th>
                <th>Dosen</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($kelas = $kelas_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($kelas['kode_mk']); ?></strong></td>
                <td><?php echo htmlspecialchars($kelas['nama_mk']); ?></td>
                <td><?php echo htmlspecialchars($kelas['nama_kelas']); ?></td>
                <td><?php echo $kelas['sks']; ?></td>
                <td><?php echo htmlspecialchars($kelas['dosen_pengampu'] ?? '-'); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="text-align: center; color: var(--gray); padding: 30px;">
        <i class="fas fa-info-circle"></i> Belum mengambil mata kuliah apapun.
    </p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
