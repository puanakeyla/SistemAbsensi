<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - Sistem Absensi</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #062a4a;
            --secondary: #0b6fa6;
            --accent: #c49a6c;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f4f7fb;
            --dark: #062a4a;
            --gray: #7f8c8d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }

        body {
            background-color: var(--light);
            color: #222;
            -webkit-font-smoothing: antialiased;
        }
        
        .container {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        /* Topbar */
        .topbar {
            height: 70px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            padding: 0 22px;
            box-shadow: 0 2px 6px rgba(8,18,28,0.12);
        }

        .topbar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand .logo {
            width: 44px;
            height: 44px;
            border-radius: 6px;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 0.95rem;
        }

        .topbar .campus-name {
            font-weight: 600;
            font-size: 1.05rem;
        }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: white;
            color: var(--dark);
            padding: 18px 0;
            border-right: 1px solid #e6eef6;
            transition: width 0.3s ease;
        }
        
        .sidebar-header {
            padding: 0 20px 18px;
            text-align: left;
        }

        .sidebar-header h2 {
            font-size: 1.05rem;
            margin-bottom: 6px;
            color: var(--dark);
        }

        .sidebar-header p {
            font-size: 0.85rem;
            color: var(--gray);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }
        
        .sidebar-menu li {
            padding: 0;
        }
        
        .sidebar-menu li a {
            color: var(--dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            transition: all 0.18s ease;
            border-left: 4px solid transparent;
            border-radius: 6px;
            gap: 12px;
        }

        .sidebar-menu li a:hover {
            background-color: rgba(11,111,166,0.06);
            border-left: 4px solid var(--secondary);
            color: var(--primary);
        }

        .sidebar-menu li.active a {
            background-color: rgba(11,111,166,0.08);
            border-left: 4px solid var(--secondary);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-menu li a i {
            width: 20px;
            text-align: center;
            color: var(--secondary);
        }

        /* Collapsed sidebar */
        .sidebar.collapsed {
            width: 72px;
        }

        .sidebar.collapsed .sidebar-header p,
        .sidebar.collapsed .sidebar-header h2 {
            display: none;
        }

        .sidebar.collapsed .sidebar-menu li a {
            justify-content: center;
            padding: 12px 10px;
        }

        .sidebar.collapsed .sidebar-menu li a .label {
            display: none;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 28px;
            overflow-y: auto;
        }

        /* Hero/Banner area */
        .hero {
            background: linear-gradient(135deg, rgba(11,111,166,0.06), rgba(196,154,108,0.04));
            padding: 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 20px;
            box-shadow: 0 6px 18px rgba(10,24,40,0.03);
        }

        .hero .campus-crest {
            width: 72px;
            height: 72px;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(11,111,166,0.12), rgba(11,111,166,0.02));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary);
            font-size: 1.05rem;
        }

        .hero h2 { font-size: 1.15rem; color: var(--dark); }
        .hero p { color: var(--gray); font-size: 0.95rem; }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .header h1 {
            color: var(--dark);
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            background: white;
            padding: 8px 12px;
            border-radius: 999px;
            box-shadow: 0 2px 6px rgba(8,18,28,0.06);
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid var(--secondary);
        }
        
        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(10,24,40,0.06);
            padding: 18px;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            border-top: 4px solid rgba(11,111,166,0.14);
        }

        .card .card-header {
            background: linear-gradient(90deg, rgba(11,111,166,0.04), rgba(196,154,108,0.02));
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card .card-title { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
        }

        .fade-in { 
            animation: fadeUp .45s ease both; 
        }
        
        @keyframes fadeUp { 
            from { 
                opacity: 0; 
                transform: translateY(6px);
            } 
            to { 
                opacity:1; 
                transform: translateY(0);
            } 
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(10,24,40,0.08);
        }
        
        .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            background-color: var(--secondary);
            box-shadow: 0 4px 8px rgba(11,111,166,0.08);
        }
        
        /* Jadwal */
        .jadwal-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .jadwal-item:last-child {
            border-bottom: none;
        }
        
        .jadwal-info h4 {
            font-size: 1rem;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .jadwal-info p {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .jadwal-waktu {
            text-align: right;
        }
        
        .jadwal-waktu .waktu {
            font-weight: 600;
            color: var(--dark);
        }
        
        .jadwal-waktu .ruang {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        /* Button */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #229954;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }

        /* Course selector */
        .select-course {
            padding: 8px 12px;
            border: 1px solid #d7e7f3;
            border-radius: 8px;
            background: white;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-hadir {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success);
        }
        
        .status-izin {
            background-color: rgba(52, 152, 219, 0.2);
            color: var(--secondary);
        }
        
        .status-sakit {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning);
        }
        
        .status-alpha {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger);
        }
        
        .status-pending {
            background-color: rgba(149, 165, 166, 0.2);
            color: var(--gray);
        }
        
        /* Chart Container */
        .chart-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(10,24,40,0.06);
            padding: 18px;
            margin-bottom: 30px;
            border-top: 4px solid rgba(11,111,166,0.14);
        }
        
        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
            display: flex;
            align-items: center;
        }
        
        .chart-title i {
            margin-right: 10px;
            color: var(--secondary);
        }
        
        /* Notifikasi */
        .notifikasi {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(10,24,40,0.06);
            padding: 20px;
            border-top: 4px solid rgba(11,111,166,0.14);
        }
        
        .notifikasi-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: flex-start;
        }
        
        .notifikasi-item:last-child {
            border-bottom: none;
        }
        
        .notifikasi-item.unread {
            background-color: rgba(52, 152, 219, 0.05);
            margin: -12px -20px;
            padding: 12px 20px;
            border-left: 3px solid var(--secondary);
        }
        
        .notifikasi-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .notifikasi-icon.success {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success);
        }
        
        .notifikasi-icon.warning {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning);
        }
        
        .notifikasi-icon.danger {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger);
        }
        
        .notifikasi-icon.info {
            background-color: rgba(52, 152, 219, 0.2);
            color: var(--secondary);
        }
        
        .notifikasi-content {
            flex: 1;
        }
        
        .notifikasi-judul {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .notifikasi-tanggal {
            font-size: 0.8rem;
            color: var(--gray);
        }
        
        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.2rem;
        }
        
        .stat-info h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .stat-info p {
            font-size: 0.9rem;
            color: var(--gray);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .cards {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-info {
                margin-top: 15px;
            }

            .quick-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .quick-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                    <img src="https://ui-avatars.com/api/?name=Puan+Cindy&background=3498db&color=fff" alt="User">
                    <div>
                        <div>Puan Cindy</div>
                        <small>NIM: 2315061070</small>
                    </div>
                </div>
            </div>

            <div class="hero fade-in">
                <div class="campus-crest">BDL</div>
                <div>
                    <h2>Selamat datang, Puan Cindy</h2>
                    <p>Semoga hari ini produktif — lihat ringkasan hadir, jadwal, dan notifikasi Anda di bawah.</p>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="stat-card fade-in">
                    <div class="stat-icon" style="background-color: var(--success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3 data-target="85" id="stat1">0%</h3>
                        <p id="stat1-label">Kehadiran</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: var(--secondary);">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3 data-target="6" id="stat2">0</h3>
                        <p>Mata Kuliah</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: var(--warning);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3 data-target="2" id="stat3">0</h3>
                        <p>Izin</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: var(--danger);">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3 data-target="1" id="stat4">0</h3>
                        <p>Alpha</p>
                    </div>
                </div>
            </div>
            
            <!-- Cards -->
            <div class="cards">
                <div class="card fade-in">
                    <div class="card-header">
                        <div class="card-title"><i class="fas fa-calendar-day"></i> Jadwal Hari Ini</div>
                        <div class="card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="jadwal-item">
                            <div class="jadwal-info">
                                <h4>Pemrograman Web</h4>
                                <p>Dr. Ahmad S.T., M.T.</p>
                            </div>
                            <div class="jadwal-waktu">
                                <div class="waktu">08:00 - 10:00</div>
                                <div class="ruang">Ruang 301</div>
                            </div>
                        </div>
                        <div class="jadwal-item">
                            <div class="jadwal-info">
                                <h4>Basis Data Lanjut</h4>
                                <p>Dr. Siti M.Kom.</p>
                            </div>
                            <div class="jadwal-waktu">
                                <div class="waktu">10:30 - 12:30</div>
                                <div class="ruang">Ruang 302</div>
                            </div>
                        </div>
                        <div class="jadwal-item">
                            <div class="jadwal-info">
                                <h4>Jaringan Komputer</h4>
                                <p>Dr. Budi S.T., M.T.</p>
                            </div>
                            <div class="jadwal-waktu">
                                <div class="waktu">13:30 - 15:30</div>
                                <div class="ruang">Lab. Jaringan</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card fade-in">
                    <div class="card-header">
                        <div class="card-title"><i class="fas fa-clipboard-list"></i> Status Absensi</div>
                        <div class="card-icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="jadwal-item">
                            <div class="jadwal-info">
                                <h4>Pemrograman Web</h4>
                                <p>08:00 - 10:00</p>
                            </div>
                            <div class="jadwal-waktu">
                                <span class="status-badge status-hadir">Hadir</span>
                            </div>
                        </div>
                        <div class="jadwal-item">
                            <div class="jadwal-info">
                                <h4>Basis Data Lanjut</h4>
                                <p>10:30 - 12:30</p>
                            </div>
                            <div class="jadwal-waktu">
                                <button class="btn btn-success btn-block"><i class="fas fa-fingerprint"></i> Absen Sekarang</button>
                            </div>
                        </div>
                        <div class="jadwal-item">
                            <div class="jadwal-info">
                                <h4>Jaringan Komputer</h4>
                                <p>13:30 - 15:30</p>
                            </div>
                            <div class="jadwal-waktu">
                                <span class="status-badge status-pending">Belum Dimulai</span>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                        <canvas id="attendanceChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Chart -->
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-line"></i> Grafik Kehadiran Semester Ini</div>
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
            
            <!-- Notifikasi -->
            <div class="notifikasi">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-bell"></i> Notifikasi Terbaru</div>
                </div>
                <div class="card-body">
                    <div class="notifikasi-item unread">
                        <div class="notifikasi-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="notifikasi-content">
                            <div class="notifikasi-judul">Pengajuan Izin Disetujui</div>
                            <p>Pengajuan izin sakit tanggal 15 Oktober 2023 telah disetujui.</p>
                            <div class="notifikasi-tanggal">16 Oktober 2023, 08:30</div>
                        </div>
                    </div>
                    <div class="notifikasi-item">
                        <div class="notifikasi-icon danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="notifikasi-content">
                            <div class="notifikasi-judul">Pengajuan Izin Ditolak</div>
                            <p>Pengajuan izin tanggal 10 Oktober 2023 ditolak karena bukti tidak valid.</p>
                            <div class="notifikasi-tanggal">11 Oktober 2023, 14:15</div>
                        </div>
                    </div>
                    <div class="notifikasi-item">
                        <div class="notifikasi-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="notifikasi-content">
                            <div class="notifikasi-judul">Pengingat Absensi</div>
                            <p>Jangan lupa absen untuk mata kuliah Basis Data Lanjut hari ini pukul 10:30.</p>
                            <div class="notifikasi-tanggal">Hari ini, 09:45</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data untuk setiap mata kuliah
        const courseData = {
            pemrograman_web: {
                name: 'Pemrograman Web',
                pie: [14, 2, 1, 1], // Hadir, Izin, Sakit, Alpha
                monthly: [85, 80, 90, 82, 88, 92, 85, 80, 87, 90, 88, 85]
            },
            basis_data: {
                name: 'Basis Data Lanjut',
                pie: [12, 3, 2, 1],
                monthly: [78, 75, 82, 80, 85, 88, 83, 79, 84, 86, 85, 82]
            },
            jaringan: {
                name: 'Jaringan Komputer',
                pie: [15, 1, 1, 1],
                monthly: [88, 82, 91, 85, 90, 93, 87, 83, 89, 92, 90, 88]
            }
        };

        // Fungsi untuk menghitung rata-rata
        function average(arr) {
            if (!arr || !arr.length) return 0;
            return Math.round(arr.reduce((s,v) => s+v, 0) / arr.length);
        }

        // Inisialisasi chart
        const initialCourse = 'pemrograman_web';

        // Pie Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(attendanceCtx, {
            type: 'pie',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                datasets: [{
                    data: courseData[initialCourse].pie,
                    backgroundColor: ['#2ecc71', '#3498db', '#f39c12', '#e74c3c'],
                    borderColor: 'transparent',
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const dataArr = context.dataset.data;
                                const total = dataArr.reduce((a,b) => a + b, 0);
                                const pct = total ? Math.round((value/total) * 100) : 0;
                                return `${context.label}: ${value} pertemuan (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Line Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Persentase Kehadiran',
                    data: courseData[initialCourse].monthly,
                    borderColor: '#0b6fa6',
                    backgroundColor: 'rgba(11,111,166,0.08)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#0b6fa6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 60,
                        max: 100,
                        ticks: {
                            callback: function(value) { return value + '%'; }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Kehadiran: ${context.parsed.y}%`;
                            }
                        }
                    }
                }
            }
        });

        // Animasi angka
        function animateNumber(el, target, opts = {}) {
            const start = Number(el.textContent.replace('%', '').replace('', '')) || 0;
            const suffix = opts.suffix || '';
            const duration = opts.duration || 700;
            const startTime = performance.now();

            function step(now) {
                const progress = Math.min((now - startTime) / duration, 1);
                const value = Math.round(start + (target - start) * progress);
                el.textContent = value + suffix;
                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            }
            requestAnimationFrame(step);
        }

        // Update data berdasarkan mata kuliah yang dipilih
        function updateForCourse(key) {
            const data = courseData[key];
            if (!data) return;

            // Update pie chart
            attendanceChart.data.datasets[0].data = data.pie;
            attendanceChart.update();

            // Update monthly chart
            monthlyChart.data.datasets[0].data = data.monthly;
            monthlyChart.update();

            // Update statistik kehadiran
            const statPctEl = document.getElementById('stat1');
            const statLabelEl = document.getElementById('stat1-label');
            const avg = average(data.monthly);
            
            if (statPctEl) animateNumber(statPctEl, avg, { suffix: '%' });
            if (statLabelEl) statLabelEl.textContent = data.name;
        }

        // Toggle sidebar
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        // Restore state dari localStorage
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }
        
        toggleBtn.addEventListener('click', function() {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });

        // Inisialisasi
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial course
            updateForCourse(initialCourse);

            // Animate quick stats
            document.querySelectorAll('.quick-stats h3[data-target]').forEach(el => {
                const target = Number(el.getAttribute('data-target')) || 0;
                if (el.id !== 'stat1') {
                    animateNumber(el, target);
                }
            });

            // Event listener untuk select course
            document.getElementById('courseSelect').addEventListener('change', function() {
                updateForCourse(this.value);
            });

            // Event listener untuk tombol absen
            document.querySelectorAll('.btn-success').forEach(btn => {
                btn.addEventListener('click', function() {
                    const matkul = this.closest('.jadwal-item').querySelector('h4').textContent;
                    if (confirm(`Apakah Anda yakin ingin melakukan absensi untuk ${matkul}?`)) {
                        this.innerHTML = '<i class="fas fa-check"></i> Berhasil Absen';
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                        this.disabled = true;
                        
                        // Show success notification
                        alert(`Absensi untuk ${matkul} berhasil!`);
                    }
                });
            });
        });
    </script>
</body>
</html>