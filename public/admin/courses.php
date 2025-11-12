<?php
require_once __DIR__ . '/includes/header.php';

$res = $conn->query('SELECT id, kode_mk, nama_mk, sks, created_at FROM mata_kuliah WHERE deleted_at IS NULL ORDER BY kode_mk');

?>

            <h1><i class="fas fa-book"></i> Mata Kuliah</h1>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2><i class="fas fa-list"></i> Daftar Mata Kuliah</h2>
                    <a href="index.php" class="btn" style="font-size: 0.8rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <table>
                <thead><tr><th style="width: 50px;">No</th><th style="width: 120px;">Kode</th><th>Nama</th><th style="width: 80px;">SKS</th><th style="width: 180px;">Dibuat</th></tr></thead>
                <tbody>
                <?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($row['kode_mk']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
                <td><?php echo htmlspecialchars($row['sks']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" style="text-align: center; color: var(--gray);">Tidak ada data mata kuliah.</td></tr>
                <?php endif; ?>
                </tbody></table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>