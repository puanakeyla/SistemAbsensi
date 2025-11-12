<?php
require_once __DIR__ . '/includes/header.php';

$res = $conn->query('SELECT k.id, k.nama_kelas, mk.kode_mk, mk.nama_mk, k.created_at FROM kelas k JOIN mata_kuliah mk ON k.id_mk = mk.id WHERE k.deleted_at IS NULL ORDER BY k.nama_kelas');

?>

			<h1>Kelas</h1>
			<p><a href="index.php">&larr; Kembali</a></p>
			<div class="card">
			<table>
			<thead><tr><th>No</th><th>Nama Kelas</th><th>Mata Kuliah</th><th>Dibuat</th></tr></thead>
			<tbody>
			<?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
			<tr>
			<td><?php echo $i++; ?></td>
			<td><?php echo htmlspecialchars($row['nama_kelas']); ?></td>
			<td><?php echo htmlspecialchars($row['kode_mk'].' - '.$row['nama_mk']); ?></td>
			<td><?php echo htmlspecialchars($row['created_at']); ?></td>
			</tr>
			<?php endwhile; else: ?>
			<tr><td colspan="4">Tidak ada data.</td></tr>
			<?php endif; ?>
			</tbody></table>
			</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
