<?php
require_once __DIR__ . '/includes/header.php';

// List admins
$res = $conn->query('SELECT id, username, nama, email, created_at FROM admin WHERE deleted_at IS NULL ORDER BY username');

?>

			<h1>Manajemen Admin</h1>
			<p><a href="index.php">&larr; Kembali</a></p>
			<div class="card">
			<table>
			<thead><tr><th>No</th><th>Username</th><th>Nama</th><th>Email</th><th>Dibuat</th></tr></thead>
			<tbody>
			<?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
			<tr>
			<td><?php echo $i++; ?></td>
			<td><?php echo htmlspecialchars($row['username']); ?></td>
			<td><?php echo htmlspecialchars($row['nama']); ?></td>
			<td><?php echo htmlspecialchars($row['email']); ?></td>
			<td><?php echo htmlspecialchars($row['created_at']); ?></td>
			</tr>
			<?php endwhile; else: ?>
			<tr><td colspan="5">Tidak ada data.</td></tr>
			<?php endif; ?>
			</tbody></table>
			</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
