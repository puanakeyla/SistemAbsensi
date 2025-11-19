<?php
session_start();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/Models/MahasiswaModel.php';

function formatTimeRange(?string $start, ?string $end): string {
    $startTime = $start ? date('H:i', strtotime($start)) : null;
    $endTime = $end ? date('H:i', strtotime($end)) : null;

    if ($startTime && $endTime) {
        return $startTime . ' - ' . $endTime;
    }
    if ($startTime) {
        return $startTime;
    }
    if ($endTime) {
        return $endTime;
    }

    return '-';
}

function formatTanggalIndonesia(?string $datetime): string {
    if (!$datetime) {
        return '--';
    }

    $timestamp = strtotime($datetime);
    if (!$timestamp) {
        return '--';
    }

    return date('d M Y, H:i', $timestamp);
}

function mapNotificationMeta(?string $type): array {
    $map = [
        'izin_disetujui' => ['judul' => 'Pengajuan Izin Disetujui', 'tipe' => 'success'],
        'izin_ditolak' => ['judul' => 'Pengajuan Izin Ditolak', 'tipe' => 'danger'],
        'pengingat_absen' => ['judul' => 'Pengingat Absensi', 'tipe' => 'warning'],
        'absensi_dibuka' => ['judul' => 'Absensi Telah Dibuka', 'tipe' => 'info'],
    ];

    return $map[$type] ?? ['judul' => 'Notifikasi', 'tipe' => 'info'];
}

function mapStatusMeta(?string $rawStatus): ?array {
    $map = [
        'Hadir' => ['key' => 'hadir', 'label' => 'Hadir'],
        'Izin' => ['key' => 'izin', 'label' => 'Izin'],
        'Sakit' => ['key' => 'sakit', 'label' => 'Sakit'],
        'Alpha' => ['key' => 'alpha', 'label' => 'Alpha'],
    ];

    return $map[$rawStatus] ?? null;
}

function isUpcomingClass(?string $startTime): bool {
    if (!$startTime) {
        return false;
    }

    $now = new DateTime();
    $start = DateTime::createFromFormat('H:i:s', $startTime) ?: DateTime::createFromFormat('H:i', $startTime);

    if (!$start) {
        return false;
    }

    $start->setDate((int) $now->format('Y'), (int) $now->format('m'), (int) $now->format('d'));
    return $start > $now;
}

$defaultMahasiswa = [
    'nama' => 'Puan Cindy',
    'nim' => '2315061070',
    'avatar' => 'https://ui-avatars.com/api/?name=Puan+Cindy&background=3498db&color=fff'
];

$mahasiswa = $defaultMahasiswa;

$statistik = [
    'kehadiran' => 85,
    'mata_kuliah' => 6,
    'izin' => 2,
    'alpha' => 1
];

$jadwal_hari_ini = [
    [
        'matkul' => 'Pemrograman Web',
        'detail' => 'PW101 · Kelas A',
        'waktu' => '08:00 - 10:00',
        'lokasi' => 'Ruang 301',
        'jadwal_id' => null
    ],
    [
        'matkul' => 'Basis Data Lanjut',
        'detail' => 'BDL202 · Kelas B',
        'waktu' => '10:30 - 12:30',
        'lokasi' => 'Ruang 302',
        'jadwal_id' => null
    ],
    [
        'matkul' => 'Jaringan Komputer',
        'detail' => 'JK303 · Lab Jaringan',
        'waktu' => '13:30 - 15:30',
        'lokasi' => 'Lab. Jaringan',
        'jadwal_id' => null
    ]
];

$status_absensi = [
    [
        'matkul' => 'Pemrograman Web',
        'detail' => 'PW101 · Kelas A',
        'waktu' => '08:00 - 10:00',
        'status' => 'hadir',
        'status_label' => 'Hadir',
        'jadwal_id' => null
    ],
    [
        'matkul' => 'Basis Data Lanjut',
        'detail' => 'BDL202 · Kelas B',
        'waktu' => '10:30 - 12:30',
        'status' => 'belum_absen',
        'status_label' => 'Belum Absen',
        'jadwal_id' => null
    ],
    [
        'matkul' => 'Jaringan Komputer',
        'detail' => 'JK303 · Lab Jaringan',
        'waktu' => '13:30 - 15:30',
        'status' => 'pending',
        'status_label' => 'Belum Dimulai',
        'jadwal_id' => null
    ]
];

$notifikasi = [
    [
        'judul' => 'Pengajuan Izin Disetujui',
        'pesan' => 'Pengajuan izin sakit tanggal 15 Oktober 2023 telah disetujui.',
        'tanggal' => '16 Okt 2023, 08:30',
        'tipe' => 'success',
        'unread' => true
    ],
    [
        'judul' => 'Pengajuan Izin Ditolak',
        'pesan' => 'Pengajuan izin tanggal 10 Oktober 2023 ditolak karena bukti tidak valid.',
        'tanggal' => '11 Okt 2023, 14:15',
        'tipe' => 'danger',
        'unread' => false
    ],
    [
        'judul' => 'Pengingat Absensi',
        'pesan' => 'Jangan lupa absen untuk mata kuliah Basis Data Lanjut hari ini pukul 10:30.',
        'tanggal' => 'Hari ini, 09:45',
        'tipe' => 'warning',
        'unread' => false
    ]
];

try {
    $database = new Database();
    $pdo = $database->getConnection();

    if ($pdo) {
        $model = new MahasiswaModel($pdo);
        $nim = $_SESSION['mahasiswa_nim'] ?? ($_GET['nim'] ?? null);

        $mahasiswaData = [];
        if ($nim) {
            $mahasiswaData = $model->getMahasiswaByNIM($nim);
        }

        if (!$mahasiswaData) {
            $mahasiswaData = $model->getFirstMahasiswa();
        }

        if ($mahasiswaData) {
            $mahasiswa = [
                'nama' => $mahasiswaData['nama'] ?? $defaultMahasiswa['nama'],
                'nim' => $mahasiswaData['nim'] ?? $defaultMahasiswa['nim'],
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($mahasiswaData['nama'] ?? $defaultMahasiswa['nama']) . '&background=3498db&color=fff'
            ];

            $mahasiswaId = (int) ($mahasiswaData['id'] ?? 0);

            if ($mahasiswaId > 0) {
                $statsRaw = $model->getStatistikKehadiran($mahasiswaId);
                $totalPertemuan = (int) ($statsRaw['total_pertemuan'] ?? 0);
                $hadir = (int) ($statsRaw['hadir'] ?? 0);
                $kehadiran = $totalPertemuan > 0 ? (int) round(($hadir / $totalPertemuan) * 100) : 0;

                $statistik = [
                    'kehadiran' => max(0, min(100, $kehadiran)),
                    'mata_kuliah' => $model->getTotalMataKuliah($mahasiswaId),
                    'izin' => (int) ($statsRaw['izin'] ?? 0),
                    'alpha' => (int) ($statsRaw['alpha'] ?? 0)
                ];

                $jadwalRows = $model->getJadwalHariIni($mahasiswaId);
                if ($jadwalRows) {
                    $jadwal_hari_ini = array_map(function ($row) {
                        $detailParts = [];
                        if (!empty($row['kode_mk'])) {
                            $detailParts[] = $row['kode_mk'];
                        }
                        if (!empty($row['nama_kelas'])) {
                            $detailParts[] = 'Kelas ' . $row['nama_kelas'];
                        }

                        return [
                            'matkul' => $row['nama_mk'] ?? '-',
                            'detail' => $detailParts ? implode(' · ', $detailParts) : '-',
                            'waktu' => formatTimeRange($row['jam_mulai'] ?? null, $row['jam_selesai'] ?? null),
                            'lokasi' => isset($row['radius_meter']) && $row['radius_meter'] !== null
                                ? 'Radius ' . (int) $row['radius_meter'] . ' m'
                                : 'Lokasi belum tersedia',
                            'jadwal_id' => $row['id'] ?? null
                        ];
                    }, $jadwalRows);
                } else {
                    $jadwal_hari_ini = [];
                }

                $statusRows = $model->getStatusAbsensiHariIni($mahasiswaId);
                if ($statusRows) {
                    $status_absensi = array_map(function ($row) {
                        $detailParts = [];
                        if (!empty($row['nama_kelas'])) {
                            $detailParts[] = 'Kelas ' . $row['nama_kelas'];
                        }

                        $statusMeta = mapStatusMeta($row['status'] ?? null);

                        if ($statusMeta) {
                            $statusKey = $statusMeta['key'];
                            $statusLabel = $statusMeta['label'];
                        } else {
                            $isUpcoming = isUpcomingClass($row['jam_mulai'] ?? null);
                            $statusKey = $isUpcoming ? 'pending' : 'belum_absen';
                            $statusLabel = $isUpcoming ? 'Belum Dimulai' : 'Belum Absen';
                        }

                        return [
                            'matkul' => $row['nama_mk'] ?? '-',
                            'detail' => $detailParts ? implode(' · ', $detailParts) : '-',
                            'waktu' => formatTimeRange($row['jam_mulai'] ?? null, $row['jam_selesai'] ?? null),
                            'status' => $statusKey,
                            'status_label' => $statusLabel,
                            'jadwal_id' => $row['jadwal_id'] ?? null
                        ];
                    }, $statusRows);
                } elseif (!empty($jadwal_hari_ini)) {
                    $status_absensi = array_map(function ($row) {
                        return [
                            'matkul' => $row['matkul'],
                            'detail' => $row['detail'],
                            'waktu' => $row['waktu'],
                            'status' => 'belum_absen',
                            'status_label' => 'Belum Absen',
                            'jadwal_id' => $row['jadwal_id']
                        ];
                    }, $jadwal_hari_ini);
                } else {
                    $status_absensi = [];
                }

                $notifRows = $model->getNotifikasi($mahasiswaId);
                if ($notifRows) {
                    $notifikasi = array_map(function ($row) {
                        $meta = mapNotificationMeta($row['type'] ?? null);
                        return [
                            'judul' => $meta['judul'],
                            'pesan' => $row['message'] ?? '',
                            'tanggal' => formatTanggalIndonesia($row['created_at'] ?? null),
                            'tipe' => $meta['tipe'],
                            'unread' => !((bool) ($row['is_read'] ?? false))
                        ];
                    }, $notifRows);
                } else {
                    $notifikasi = [];
                }
            }
        }
    }
} catch (Throwable $th) {
    error_log('Dashboard Mahasiswa data error: ' . $th->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - Sistem Absensi</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="topbar">
        <div class="brand">
            <div class="logo">BDL</div>
            <div class="campus-name">Universitas BDL — Sistem Absensi</div>
        </div>
        <div style="margin-left:auto; display:flex; align-items:center; gap:12px;">
            <button id="sidebarToggle" title="Toggle sidebar" class="btn" style="background:rgba(255,255,255,0.08); color:white; border-radius:8px; padding:8px 10px;">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-user-graduate"></i> Sistem Absensi</h2>
                <p>Mahasiswa</p>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="#"><i class="fas fa-tachometer-alt"></i><span class="label">Dashboard</span></a></li>
                <li><a href="#"><i class="fas fa-clipboard-check"></i><span class="label">Absensi</span></a></li>
                <li><a href="#"><i class="fas fa-file-medical"></i><span class="label">Pengajuan Izin</span></a></li>
                <li><a href="#"><i class="fas fa-history"></i><span class="label">Riwayat Kehadiran</span></a></li>
                <li><a href="#"><i class="fas fa-cog"></i><span class="label">Pengaturan</span></a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i><span class="label">Keluar</span></a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Mahasiswa</h1>
                <div class="user-info">
                    <img src="<?php echo htmlspecialchars($mahasiswa['avatar'], ENT_QUOTES, 'UTF-8'); ?>" alt="User">
                    <div>
                        <div><?php echo htmlspecialchars($mahasiswa['nama'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <small>NIM: <?php echo htmlspecialchars($mahasiswa['nim'], ENT_QUOTES, 'UTF-8'); ?></small>
                    </div>
                </div>
            </div>

            <div class="hero fade-in">
                <div class="campus-crest">BDL</div>
                <div>
                    <h2>Selamat datang, <?php echo htmlspecialchars($mahasiswa['nama'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p>Semoga hari ini produktif — lihat ringkasan hadir, jadwal, dan notifikasi Anda di bawah.</p>
                </div>
            </div>
            
            <!-- Main grid: left (content) + right (panel) -->
            <div class="main-grid">
                <div class="main-left">

                <!-- Unified Overview Card -->
                <div class="overview-card card fade-in">
                    <div class="overview-stats">
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--success);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <h3 data-target="<?php echo (int) $statistik['kehadiran']; ?>" id="stat1">0%</h3>
                                <p id="stat1-label">Kehadiran</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--secondary);">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-info">
                                <h3 data-target="<?php echo (int) $statistik['mata_kuliah']; ?>" id="stat2">0</h3>
                                <p>Mata Kuliah</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--warning);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <h3 data-target="<?php echo (int) $statistik['izin']; ?>" id="stat3">0</h3>
                                <p>Izin</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--danger);">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="stat-info">
                                <h3 data-target="<?php echo (int) $statistik['alpha']; ?>" id="stat4">0</h3>
                                <p>Alpha</p>
                            </div>
                        </div>
                    </div>

                    <div class="overview-body">
                        <div class="overview-left">
                            <h3 class="small-title"><i class="fas fa-calendar-day"></i> Jadwal Hari Ini</h3>
                            <div class="jadwal-list">
                                <?php if (empty($jadwal_hari_ini)): ?>
                                    <p style="color: var(--gray); font-size: 0.9rem;">Tidak ada jadwal untuk hari ini.</p>
                                <?php else: ?>
                                    <?php foreach ($jadwal_hari_ini as $jadwal): ?>
                                    <div class="jadwal-item">
                                        <div class="jadwal-info">
                                            <h4><?php echo htmlspecialchars($jadwal['matkul'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                            <?php if (!empty($jadwal['detail']) && $jadwal['detail'] !== '-'): ?>
                                                <p><?php echo htmlspecialchars($jadwal['detail'], ENT_QUOTES, 'UTF-8'); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="jadwal-waktu">
                                            <div class="waktu"><?php echo htmlspecialchars($jadwal['waktu'], ENT_QUOTES, 'UTF-8'); ?></div>
                                            <div class="ruang"><?php echo htmlspecialchars($jadwal['lokasi'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="overview-right">
                            <h3 class="small-title"><i class="fas fa-clipboard-list"></i> Status Absensi</h3>
                            <div class="status-list">
                                <?php if (empty($status_absensi)): ?>
                                    <p style="color: var(--gray); font-size: 0.9rem;">Belum ada status absensi untuk ditampilkan.</p>
                                <?php else: ?>
                                    <?php foreach ($status_absensi as $absensi): ?>
                                    <div class="jadwal-item">
                                        <div class="jadwal-info">
                                            <h4><?php echo htmlspecialchars($absensi['matkul'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                            <?php if (!empty($absensi['detail']) && $absensi['detail'] !== '-'): ?>
                                                <p><?php echo htmlspecialchars($absensi['detail'], ENT_QUOTES, 'UTF-8'); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="jadwal-waktu">
                                            <div class="waktu"><?php echo htmlspecialchars($absensi['waktu'], ENT_QUOTES, 'UTF-8'); ?></div>
                                            <?php
                                                $statusKey = $absensi['status'] ?? 'belum_absen';
                                                $statusLabel = htmlspecialchars($absensi['status_label'] ?? 'Belum Absen', ENT_QUOTES, 'UTF-8');
                                            ?>
                                            <?php if ($statusKey === 'belum_absen'): ?>
                                                <button class="btn btn-success btn-block"
                                                    data-matkul="<?php echo htmlspecialchars($absensi['matkul'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    <?php if (!empty($absensi['jadwal_id'])): ?>
                                                        data-jadwal="<?php echo htmlspecialchars((string) $absensi['jadwal_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    <?php endif; ?>
                                                >
                                                    <i class="fas fa-fingerprint"></i> Absen Sekarang
                                                </button>
                                            <?php else: ?>
                                                <?php
                                                    $badgeClassMap = [
                                                        'hadir' => 'status-hadir',
                                                        'izin' => 'status-izin',
                                                        'sakit' => 'status-sakit',
                                                        'alpha' => 'status-alpha',
                                                        'pending' => 'status-pending'
                                                    ];
                                                    $badgeClass = $badgeClassMap[$statusKey] ?? 'status-pending';
                                                ?>
                                                <span class="status-badge <?php echo $badgeClass; ?>"><?php echo $statusLabel; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Chart -->
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-chart-line"></i> Grafik Kehadiran Semester Ini</div>
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>

                </div> <!-- .main-left -->

                <aside class="main-right">
                    <div class="card fade-in">
                        <div class="card-header" style="align-items:center; gap:12px;">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div class="card-title"><i class="fas fa-chart-pie"></i> Statistik Kehadiran</div>
                                <select id="courseSelect" class="select-course" aria-label="Pilih Mata Kuliah">
                                    <option value="pemrograman_web">Pemrograman Web</option>
                                    <option value="basis_data">Basis Data Lanjut</option>
                                    <option value="jaringan">Jaringan Komputer</option>
                                </select>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="attendanceChart" height="160"></canvas>
                        </div>
                    </div>

                    <div class="notifikasi">
                        <div class="card-header">
                            <div class="card-title"><i class="fas fa-bell"></i> Notifikasi Terbaru</div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($notifikasi)): ?>
                                <p style="color: var(--gray); font-size: 0.9rem;">Belum ada notifikasi terbaru.</p>
                            <?php else: ?>
                                <?php foreach ($notifikasi as $notif): ?>
                                <?php
                                    $icon = 'clock';
                                    if ($notif['tipe'] === 'success') {
                                        $icon = 'check-circle';
                                    } elseif ($notif['tipe'] === 'danger') {
                                        $icon = 'times-circle';
                                    } elseif ($notif['tipe'] === 'info') {
                                        $icon = 'info-circle';
                                    }
                                ?>
                                <div class="notifikasi-item <?php echo $notif['unread'] ? 'unread' : ''; ?>">
                                    <div class="notifikasi-icon <?php echo htmlspecialchars($notif['tipe'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="fas fa-<?php echo $icon; ?>"></i>
                                    </div>
                                    <div class="notifikasi-content">
                                        <div class="notifikasi-judul"><?php echo htmlspecialchars($notif['judul'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        <p><?php echo htmlspecialchars($notif['pesan'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <div class="notifikasi-tanggal"><?php echo htmlspecialchars($notif['tanggal'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
