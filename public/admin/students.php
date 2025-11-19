<?php
require_once __DIR__ . '/includes/header.php';

// Handle delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE mahasiswa SET deleted_at = NOW() WHERE id = $id");
    header('Location: students.php?msg=deleted');
    exit;
}

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = "WHERE deleted_at IS NULL";
if ($search) {
    $search_safe = $conn->real_escape_string($search);
    $where .= " AND (nim LIKE '%$search_safe%' OR nama LIKE '%$search_safe%' OR email LIKE '%$search_safe%')";
}

$res = $conn->query("SELECT id, nim, nama, email, no_telp, status, created_at FROM mahasiswa $where ORDER BY nama");

?>

            <h1><i class="fas fa-users"></i> Manajemen Mahasiswa</h1>
            
            <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #28a745;">
                <?php 
                if ($_GET['msg'] == 'added') echo '✓ Mahasiswa berhasil ditambahkan!';
                if ($_GET['msg'] == 'updated') echo '✓ Mahasiswa berhasil diupdate!';
                if ($_GET['msg'] == 'deleted') echo '✓ Mahasiswa berhasil dihapus!';
                ?>
            </div>
            <?php endif; ?>
            
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <h2><i class="fas fa-list"></i> Daftar Mahasiswa</h2>
                    <div style="display: flex; gap: 10px;">
                        <form method="GET" style="margin: 0;">
                            <input type="text" name="search" placeholder="Cari NIM, Nama, Email..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; width: 250px;">
                            <button type="submit" class="btn" style="background: var(--secondary); padding: 8px 16px;"><i class="fas fa-search"></i></button>
                        </form>
                        <a href="student_form.php" class="btn" style="background: var(--success); font-size: 0.8rem;"><i class="fas fa-plus"></i> Tambah</a>
                        <a href="index.php" class="btn" style="font-size: 0.8rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 120px;">NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th style="width: 120px;">Telepon</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; if ($res && $res->num_rows > 0): while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['no_telp'] ?? '-'); ?></td>
                            <td>
                                <?php 
                                $status = htmlspecialchars($row['status']);
                                $class = $status == 'aktif' ? 'status-success' : 'status-warning';
                                ?>
                                <span class="status-badge <?php echo $class; ?>"><?php echo ucfirst($status); ?></span>
                            </td>
                            <td>
                                <a href="student_view.php?id=<?php echo $row['id']; ?>" class="btn-sm" title="Lihat Detail" style="background: var(--secondary); color: white; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; display: inline-block; margin: 2px;"><i class="fas fa-eye"></i></a>
                                <a href="student_form.php?id=<?php echo $row['id']; ?>" class="btn-sm" title="Edit" style="background: var(--warning); color: white; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; display: inline-block; margin: 2px;"><i class="fas fa-edit"></i></a>
                                <a href="students.php?delete=1&id=<?php echo $row['id']; ?>" class="btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus mahasiswa ini?')" style="background: var(--danger); color: white; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; display: inline-block; margin: 2px;"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--gray);">Tidak ada data mahasiswa.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
