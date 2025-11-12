<?php
require_once __DIR__ . '/includes/header.php';

// List pengajuan izin
$res = $conn->query('SELECT p.id, m.nim, m.nama, mk.nama_mk, p.tanggal, p.alasan, p.status FROM pengajuan_izin p JOIN mahasiswa m ON p.id_mahasiswa = m.id JOIN jadwal j ON p.id_jadwal = j.id JOIN kelas k ON j.id_kelas = k.id JOIN mata_kuliah mk ON k.id_mk = mk.id ORDER BY p.created_at DESC');

?>

			<h1>Pengajuan Izin</h1>
			<p><a href="index.php">&larr; Kembali</a></p>
			<div class="card">
			<table>
			<thead><tr><th>No</th><th>NIM</th><th>Nama</th><th>Mata Kuliah</th><th>Tanggal</th><th>Alasan</th><th>Status</th></tr></thead>
			<tbody>
			<?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
			<tr>
			<td><?php echo $i++; ?></td>
			<td><?php echo htmlspecialchars($row['nim']); ?></td>
			<td><?php echo htmlspecialchars($row['nama']); ?></td>
			<td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
			<td><?php echo htmlspecialchars($row['tanggal']); ?></td>
			<td><?php echo htmlspecialchars($row['alasan']); ?></td>
			<td><?php echo htmlspecialchars($row['status']); ?></td>
			</tr>
			<?php endwhile; else: ?>
			<tr><td colspan="7">Tidak ada data.</td></tr>
			<?php endif; ?>
			</tbody></table>
			</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
