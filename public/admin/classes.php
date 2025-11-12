<?php
require_once __DIR__ . '/includes/header.php';

$res = $conn->query('SELECT k.id, k.nama_kelas, mk.kode_mk, mk.nama_mk, k.created_at FROM kelas k JOIN mata_kuliah mk ON k.id_mk = mk.id WHERE k.deleted_at IS NULL ORDER BY k.nama_kelas');

?>

            <h1><i class="fas fa-chalkboard"></i> Kelas</h1>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2><i class="fas fa-list"></i> Daftar Kelas</h2>
                    <a href="index.php" class="btn" style="font-size: 0.8rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <table>
                <thead><tr><th style="width: 50px;">No</th><th style="width: 150px;">Nama Kelas</th><th>Mata Kuliah</th><th style="width: 180px;">Dibuat</th></tr></thead>
                <tbody>
                <?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($row['nama_kelas']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['kode_mk'].' - '.$row['nama_mk']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="4" style="text-align: center; color: var(--gray);">Tidak ada data kelas.</td></tr>
                <?php endif; ?>
                </tbody></table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>