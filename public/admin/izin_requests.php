<?php
require_once __DIR__ . '/includes/header.php';

// List pengajuan izin
$res = $conn->query('SELECT p.id, m.nim, m.nama, mk.nama_mk, p.tanggal, p.alasan, p.status FROM pengajuan_izin p JOIN mahasiswa m ON p.id_mahasiswa = m.id JOIN jadwal j ON p.id_jadwal = j.id JOIN kelas k ON j.id_kelas = k.id JOIN mata_kuliah mk ON k.id_mk = mk.id ORDER BY p.created_at DESC');

?>

            <h1><i class="fas fa-file-medical"></i> Pengajuan Izin</h1>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2><i class="fas fa-list"></i> Daftar Pengajuan Izin</h2>
                    <a href="index.php" class="btn" style="font-size: 0.8rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <table>
                <thead><tr><th style="width: 50px;">No</th><th style="width: 120px;">NIM</th><th>Nama</th><th>Mata Kuliah</th><th style="width: 120px;">Tanggal</th><th>Alasan</th><th style="width: 100px;">Status</th></tr></thead>
                <tbody>
                <?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
                <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                <td><?php echo htmlspecialchars(substr($row['alasan'], 0, 50)) . (strlen($row['alasan']) > 50 ? '...' : ''); ?></td>
                <td>
                    <?php 
                    $status = htmlspecialchars($row['status']);
                    $class = '';
                    if ($status == 'disetujui') { $class = 'status-success'; }
                    elseif ($status == 'ditolak') { $class = 'status-danger'; }
                    else { $class = 'status-warning'; }
                    ?>
                    <span class="status-badge <?php echo $class; ?>"><?php echo ucfirst($status); ?></span>
                </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="7" style="text-align: center; color: var(--gray);">Tidak ada pengajuan izin.</td></tr>
                <?php endif; ?>
                </tbody></table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>