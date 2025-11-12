<?php
require_once __DIR__ . '/includes/header.php';

// List admins
$res = $conn->query('SELECT id, username, nama, email, created_at FROM admin WHERE deleted_at IS NULL ORDER BY username');

?>

            <h1><i class="fas fa-user-tie"></i> Manajemen Admin</h1>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2><i class="fas fa-list"></i> Daftar Admin</h2>
                    <a href="index.php" class="btn" style="font-size: 0.8rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <table>
                <thead><tr><th style="width: 50px;">No</th><th style="width: 150px;">Username</th><th>Nama</th><th>Email</th><th style="width: 180px;">Dibuat</th></tr></thead>
                <tbody>
                <?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" style="text-align: center; color: var(--gray);">Tidak ada data admin.</td></tr>
                <?php endif; ?>
                </tbody></table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>