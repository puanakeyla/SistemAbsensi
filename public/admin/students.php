<?php
require_once __DIR__ . '/includes/header.php';

// Simple list mahasiswa (conn sudah tersedia dari header.php)
$res = $conn->query('SELECT id, nim, nama, email, created_at FROM mahasiswa WHERE deleted_at IS NULL ORDER BY nama');

?>

            <h1><i class="fas fa-users"></i> Manajemen Mahasiswa</h1>
            
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2><i class="fas fa-list"></i> Daftar Mahasiswa</h2>
                    <a href="index.php" class="btn" style="font-size: 0.8rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 150px;">NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th style="width: 180px;">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--gray);">Tidak ada data mahasiswa.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
