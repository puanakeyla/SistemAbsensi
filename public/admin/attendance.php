<?php
require_once __DIR__ . '/includes/header.php';

// Simple recent absensi (conn sudah tersedia)
$res = $conn->query("SELECT a.id, m.nim, m.nama, mk.nama_mk, a.tanggal, a.status FROM absensi a JOIN mahasiswa m ON a.id_mahasiswa = m.id JOIN jadwal j ON a.id_jadwal = j.id JOIN kelas k ON j.id_kelas = k.id JOIN mata_kuliah mk ON k.id_mk = mk.id WHERE a.deleted_at IS NULL ORDER BY a.tanggal DESC LIMIT 100");

?>

            <h1>Rekap Absensi</h1>
            <p><a href="index.php">&larr; Kembali</a></p>
            <div class="card">
            <table>
                <thead>
                    <tr><th>No</th><th>NIM</th><th>Nama</th><th>Mata Kuliah</th><th>Tanggal</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['nim']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="6">Tidak ada data.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
