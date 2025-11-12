<?php
require_once __DIR__ . '/includes/header.php';

$res = $conn->query("SELECT j.id, k.nama_kelas, j.hari, j.jam_mulai, j.jam_selesai, j.created_at FROM jadwal j JOIN kelas k ON j.id_kelas = k.id WHERE j.deleted_at IS NULL ORDER BY k.nama_kelas, j.hari");

?>

			<h1>Jadwal</h1>
			<p><a href="index.php">&larr; Kembali</a></p>
			<div class="card">
			<table>
			<thead><tr><th>No</th><th>Kelas</th><th>Hari</th><th>Jam</th><th>Dibuat</th></tr></thead>
			<tbody>
			<?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
			<tr>
			<td><?php echo $i++; ?></td>
			<td><?php echo htmlspecialchars($row['nama_kelas']); ?></td>
			<td><?php echo htmlspecialchars($row['hari']); ?></td>
			<td><?php echo htmlspecialchars($row['jam_mulai'].' - '.$row['jam_selesai']); ?></td>
			<td><?php echo htmlspecialchars($row['created_at']); ?></td>
			</tr>
			<?php endwhile; else: ?>
			<tr><td colspan="5">Tidak ada data.</td></tr>
			<?php endif; ?>
			</tbody></table>
			</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
