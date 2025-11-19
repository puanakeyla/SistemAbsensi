<?php
// Public page - no auth required for students
require_once __DIR__ . '/../../config/db.php';

$qr_code = isset($_GET['code']) ? $_GET['code'] : '';
$session_info = null;
$success = false;
$error = '';

if ($qr_code) {
    // Get session info
    $result = $conn->query("
        SELECT 
            ases.*,
            mk.nama_mk,
            k.nama_kelas,
            j.hari,
            j.jam_mulai,
            j.jam_selesai,
            j.ruangan
        FROM absensi_session ases
        JOIN jadwal j ON ases.id_jadwal = j.id
        JOIN kelas k ON j.id_kelas = k.id
        JOIN mata_kuliah mk ON k.id_mk = mk.id
        WHERE ases.qr_code = '$qr_code' AND ases.status = 'aktif'
    ");
    
    if ($result && $result->num_rows > 0) {
        $session_info = $result->fetch_assoc();
    }
}

// Handle scan submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nim'])) {
    $nim = $conn->real_escape_string($_POST['nim']);
    $qr_code = $conn->real_escape_string($_POST['qr_code']);
    
    // Get session
    $session = $conn->query("SELECT * FROM absensi_session WHERE qr_code = '$qr_code' AND status = 'aktif'")->fetch_assoc();
    
    if ($session) {
        // Get mahasiswa
        $mhs = $conn->query("SELECT * FROM mahasiswa WHERE nim = '$nim' AND deleted_at IS NULL")->fetch_assoc();
        
        if ($mhs) {
            // Check if already absent
            $check = $conn->query("SELECT id FROM absensi WHERE id_mahasiswa = {$mhs['id']} AND id_jadwal = {$session['id_jadwal']} AND tanggal = '{$session['tanggal']}'");
            
            if ($check->num_rows > 0) {
                $error = 'Anda sudah absen untuk mata kuliah ini!';
            } else {
                // Check if student is enrolled in this class
                $enrolled = $conn->query("
                    SELECT mk.id 
                    FROM mahasiswa_kelas mkls
                    JOIN kelas k ON mkls.id_kelas = k.id
                    JOIN jadwal j ON k.id = j.id_kelas
                    WHERE mkls.id_mahasiswa = {$mhs['id']} AND j.id = {$session['id_jadwal']}
                ");
                
                if ($enrolled->num_rows > 0) {
                    // Insert absensi
                    $sql = "INSERT INTO absensi (id_mahasiswa, id_jadwal, id_session, tanggal, status, scan_method, verified) 
                            VALUES ({$mhs['id']}, {$session['id_jadwal']}, {$session['id']}, '{$session['tanggal']}', 'Hadir', 'qr', TRUE)";
                    
                    if ($conn->query($sql)) {
                        $success = true;
                    } else {
                        $error = 'Gagal menyimpan absensi: ' . $conn->error;
                    }
                } else {
                    $error = 'Anda tidak terdaftar di kelas ini!';
                }
            }
        } else {
            $error = 'NIM tidak ditemukan!';
        }
    } else {
        $error = 'Session tidak valid atau sudah ditutup!';
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code - Absensi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header i {
            font-size: 4rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 16px;
        }
        
        .header h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 8px;
        }
        
        .header p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .session-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }
        
        .session-info h2 {
            font-size: 1.3rem;
            margin-bottom: 12px;
        }
        
        .session-info p {
            margin: 6px 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }
        
        .session-info i {
            margin-right: 8px;
            opacity: 0.8;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            font-family: 'Poppins', sans-serif;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert i {
            margin-right: 8px;
        }
        
        .success-icon {
            text-align: center;
            margin: 30px 0;
        }
        
        .success-icon i {
            font-size: 5rem;
            color: #28a745;
            animation: checkmark 0.6s ease-in-out;
        }
        
        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(0deg);
            }
            50% {
                transform: scale(1.2) rotate(180deg);
            }
            100% {
                transform: scale(1) rotate(360deg);
            }
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .error-container {
            text-align: center;
            padding: 40px 20px;
        }
        
        .error-container i {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        .error-container h2 {
            color: #333;
            margin-bottom: 12px;
        }
        
        .error-container p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!$qr_code || !$session_info): ?>
        <!-- QR Code Invalid -->
        <div class="error-container">
            <i class="fas fa-exclamation-triangle"></i>
            <h2>QR Code Tidak Valid</h2>
            <p>Session tidak ditemukan atau sudah ditutup.</p>
        </div>
        
        <?php elseif ($success): ?>
        <!-- Success -->
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert alert-success">
            <i class="fas fa-check"></i>
            <strong>Absensi Berhasil!</strong><br>
            Kehadiran Anda telah tercatat.
        </div>
        <div class="session-info">
            <h2><?php echo htmlspecialchars($session_info['nama_mk']); ?></h2>
            <p><i class="fas fa-door-open"></i> Kelas <?php echo htmlspecialchars($session_info['nama_kelas']); ?></p>
            <p><i class="fas fa-calendar"></i> <?php echo date('d F Y', strtotime($session_info['tanggal'])); ?></p>
            <p><i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($session_info['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($session_info['jam_selesai'])); ?></p>
        </div>
        
        <?php else: ?>
        <!-- Form -->
        <div class="header">
            <i class="fas fa-qrcode"></i>
            <h1>Scan QR Code</h1>
            <p>Masukkan NIM Anda untuk absen</p>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <div class="session-info">
            <h2><?php echo htmlspecialchars($session_info['nama_mk']); ?></h2>
            <p><i class="fas fa-door-open"></i> Kelas <?php echo htmlspecialchars($session_info['nama_kelas']); ?></p>
            <p><i class="fas fa-calendar"></i> <?php echo date('d F Y', strtotime($session_info['tanggal'])); ?></p>
            <p><i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($session_info['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($session_info['jam_selesai'])); ?></p>
            <p><i class="fas fa-map-marker-alt"></i> Ruang <?php echo htmlspecialchars($session_info['ruangan']); ?></p>
        </div>
        
        <form method="POST">
            <input type="hidden" name="qr_code" value="<?php echo htmlspecialchars($qr_code); ?>">
            
            <div class="form-group">
                <label for="nim">
                    <i class="fas fa-id-card"></i> NIM Mahasiswa
                </label>
                <input 
                    type="text" 
                    id="nim" 
                    name="nim" 
                    placeholder="Masukkan NIM Anda" 
                    required 
                    autofocus
                    pattern="[0-9]{7,15}"
                    title="NIM harus berupa angka 7-15 digit">
            </div>
            
            <button type="submit" class="btn">
                <i class="fas fa-check-circle"></i> Submit Absensi
            </button>
        </form>
        
        <div class="back-link">
            <a href="qr_scan_page.php?code=<?php echo htmlspecialchars($qr_code); ?>">
                <i class="fas fa-redo"></i> Scan Lagi
            </a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
