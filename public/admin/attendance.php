<?php
require_once __DIR__ . '/includes/header.php';

// Simple recent absensi (conn sudah tersedia)
$res = $conn->query("SELECT a.id, m.nim, m.nama, mk.nama_mk, a.tanggal, a.status FROM absensi a JOIN mahasiswa m ON a.id_mahasiswa = m.id JOIN jadwal j ON a.id_jadwal = j.id JOIN kelas k ON j.id_kelas = k.id JOIN mata_kuliah mk ON k.id_mk = mk.id WHERE a.deleted_at IS NULL ORDER BY a.tanggal DESC LIMIT 100");

?>

            <h1><i class="fas fa-clipboard-check"></i> Rekap Absensi</h1>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2><i class="fas fa-list"></i> Daftar Absensi Terbaru</h2>
                    <a href="index.php" class="btn" style="font-size: 0.8rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 150px;">NIM</th>
                            <th>Nama</th>
                            <th>Mata Kuliah</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th style="width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; if ($res): while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
                            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                            <td>
                                <?php 
                                $status = htmlspecialchars($row['status']);
                                $class = '';
                                if ($status == 'Hadir') { $class = 'status-success'; }
                                elseif ($status == 'Izin') { $class = 'status-warning'; }
                                elseif ($status == 'Sakit') { $class = 'status-info'; }
                                elseif ($status == 'Alpha') { $class = 'status-danger'; }
                                ?>
                                <span class="status-badge <?php echo $class; ?>"><?php echo $status; ?></span>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--gray);">Tidak ada data absensi.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
