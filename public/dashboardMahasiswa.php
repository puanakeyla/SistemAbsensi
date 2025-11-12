<?php
// Data mahasiswa (biasanya dari database)
$mahasiswa = [
    'nama' => 'Puan Cindy',
    'nim' => '2315061070',
    'avatar' => 'https://ui-avatars.com/api/?name=Puan+Cindy&background=3498db&color=fff'
];

// Data statistik
$statistik = [
    'kehadiran' => 85,
    'mata_kuliah' => 6,
    'izin' => 2,
    'alpha' => 1
];

// Data jadwal hari ini
$jadwal_hari_ini = [
    [
        'matkul' => 'Pemrograman Web',
        'dosen' => 'Dr. Ahmad S.T., M.T.',
        'waktu' => '08:00 - 10:00',
        'ruang' => 'Ruang 301'
    ],
    [
        'matkul' => 'Basis Data Lanjut',
        'dosen' => 'Dr. Siti M.Kom.',
        'waktu' => '10:30 - 12:30',
        'ruang' => 'Ruang 302'
    ],
    [
        'matkul' => 'Jaringan Komputer',
        'dosen' => 'Dr. Budi S.T., M.T.',
        'waktu' => '13:30 - 15:30',
        'ruang' => 'Lab. Jaringan'
    ]
];

// Data status absensi
$status_absensi = [
    [
        'matkul' => 'Pemrograman Web',
        'waktu' => '08:00 - 10:00',
        'status' => 'hadir'
    ],
    [
        'matkul' => 'Basis Data Lanjut',
        'waktu' => '10:30 - 12:30',
        'status' => 'belum_absen'
    ],
    [
        'matkul' => 'Jaringan Komputer',
        'waktu' => '13:30 - 15:30',
        'status' => 'pending'
    ]
];

// Data notifikasi
$notifikasi = [
    [
        'judul' => 'Pengajuan Izin Disetujui',
        'pesan' => 'Pengajuan izin sakit tanggal 15 Oktober 2023 telah disetujui.',
        'tanggal' => '16 Oktober 2023, 08:30',
        'tipe' => 'success',
        'unread' => true
    ],
    [
        'judul' => 'Pengajuan Izin Ditolak',
        'pesan' => 'Pengajuan izin tanggal 10 Oktober 2023 ditolak karena bukti tidak valid.',
        'tanggal' => '11 Oktober 2023, 14:15',
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
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/style.css">
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
                    <img src="<?php echo $mahasiswa['avatar']; ?>" alt="User">
                    <div>
                        <div><?php echo $mahasiswa['nama']; ?></div>
                        <small>NIM: <?php echo $mahasiswa['nim']; ?></small>
                    </div>
                </div>
            </div>

            <div class="hero fade-in">
                <div class="campus-crest">BDL</div>
                <div>
                    <h2>Selamat datang, <?php echo $mahasiswa['nama']; ?></h2>
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
                                <h3 data-target="<?php echo $statistik['kehadiran']; ?>" id="stat1">0%</h3>
                                <p id="stat1-label">Kehadiran</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--secondary);">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-info">
                                <h3 data-target="<?php echo $statistik['mata_kuliah']; ?>" id="stat2">0</h3>
                                <p>Mata Kuliah</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--warning);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <h3 data-target="<?php echo $statistik['izin']; ?>" id="stat3">0</h3>
                                <p>Izin</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--danger);">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="stat-info">
                                <h3 data-target="<?php echo $statistik['alpha']; ?>" id="stat4">0</h3>
                                <p>Alpha</p>
                            </div>
                        </div>
                    </div>

                    <div class="overview-body">
                        <div class="overview-left">
                            <h3 class="small-title"><i class="fas fa-calendar-day"></i> Jadwal Hari Ini</h3>
                            <div class="jadwal-list">
                                <?php foreach($jadwal_hari_ini as $jadwal): ?>
                                <div class="jadwal-item">
                                    <div class="jadwal-info">
                                        <h4><?php echo $jadwal['matkul']; ?></h4>
                                        <p><?php echo $jadwal['dosen']; ?></p>
                                    </div>
                                    <div class="jadwal-waktu">
                                        <div class="waktu"><?php echo $jadwal['waktu']; ?></div>
                                        <div class="ruang"><?php echo $jadwal['ruang']; ?></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="overview-right">
                            <h3 class="small-title"><i class="fas fa-clipboard-list"></i> Status Absensi</h3>
                            <div class="status-list">
                                <?php foreach($status_absensi as $absensi): ?>
                                <div class="jadwal-item">
                                    <div class="jadwal-info">
                                        <h4><?php echo $absensi['matkul']; ?></h4>
                                        <p><?php echo $absensi['waktu']; ?></p>
                                    </div>
                                    <div class="jadwal-waktu">
                                        <?php if($absensi['status'] == 'hadir'): ?>
                                            <span class="status-badge status-hadir">Hadir</span>
                                        <?php elseif($absensi['status'] == 'belum_absen'): ?>
                                            <button class="btn btn-success btn-block" data-matkul="<?php echo $absensi['matkul']; ?>">
                                                <i class="fas fa-fingerprint"></i> Absen Sekarang
                                            </button>
                                        <?php else: ?>
                                            <span class="status-badge status-pending">Belum Dimulai</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Kehadiran moved to the right panel -->

                <!-- Chart -->
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-chart-line"></i> Grafik Kehadiran Semester Ini</div>
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>

                </div> <!-- .main-left -->

                <aside class="main-right">
                    <!-- Statistik Kehadiran (moved to right panel) -->
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

                    <!-- Notifikasi -->
                    <div class="notifikasi">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-bell"></i> Notifikasi Terbaru</div>
                </div>
                <div class="card-body">
                    <?php foreach($notifikasi as $notif): ?>
                    <div class="notifikasi-item <?php echo $notif['unread'] ? 'unread' : ''; ?>">
                        <div class="notifikasi-icon <?php echo $notif['tipe']; ?>">
                            <i class="fas fa-<?php 
                                echo $notif['tipe'] == 'success' ? 'check-circle' : 
                                    ($notif['tipe'] == 'danger' ? 'times-circle' : 'clock'); 
                            ?>"></i>
                        </div>
                        <div class="notifikasi-content">
                            <div class="notifikasi-judul"><?php echo $notif['judul']; ?></div>
                            <p><?php echo $notif['pesan']; ?></p>
                            <div class="notifikasi-tanggal"><?php echo $notif['tanggal']; ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>