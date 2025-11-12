<?php
require_once __DIR__ . '/includes/header.php';

$res = $conn->query("SELECT j.id, k.nama_kelas, j.hari, j.jam_mulai, j.jam_selesai, j.created_at FROM jadwal j JOIN kelas k ON j.id_kelas = k.id WHERE j.deleted_at IS NULL ORDER BY k.nama_kelas, j.hari");

?>

            <h1><i class="fas fa-calendar"></i> Jadwal</h1>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2><i class="fas fa-list"></i> Daftar Jadwal</h2>
                    <a href="index.php" class="btn" style="font-size: 0.8rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <table>
                <thead><tr><th style="width: 50px;">No</th><th style="width: 150px;">Kelas</th><th style="width: 120px;">Hari</th><th style="width: 180px;">Jam</th><th style="width: 180px;">Dibuat</th></tr></thead>
                <tbody>
                <?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($row['nama_kelas']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['hari']); ?></td>
                <td><?php echo htmlspecialchars($row['jam_mulai'].' - '.$row['jam_selesai']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" style="text-align: center; color: var(--gray);">Tidak ada data jadwal.</td></tr>
                <?php endif; ?>
                </tbody></table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>