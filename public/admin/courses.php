<?php
require_once __DIR__ . '/includes/header.php';

$res = $conn->query('SELECT id, kode_mk, nama_mk, sks, created_at FROM mata_kuliah WHERE deleted_at IS NULL ORDER BY kode_mk');

?>

			<h1>Mata Kuliah</h1>
			<p><a href="index.php">&larr; Kembali</a></p>
			<div class="card">
			<table>
			<thead><tr><th>No</th><th>Kode</th><th>Nama</th><th>SKS</th><th>Dibuat</th></tr></thead>
			<tbody>
			<?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
			<tr>
			<td><?php echo $i++; ?></td>
			<td><?php echo htmlspecialchars($row['kode_mk']); ?></td>
			<td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
			<td><?php echo htmlspecialchars($row['sks']); ?></td>
			<td><?php echo htmlspecialchars($row['created_at']); ?></td>
			</tr>
			<?php endwhile; else: ?>
			<tr><td colspan="5">Tidak ada data.</td></tr>
			<?php endif; ?>
			</tbody></table>
			</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
