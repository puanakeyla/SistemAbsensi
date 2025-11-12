<?php
/**
 * Test file untuk verifikasi admin panel
 * Buka: http://localhost/SistemAbsensi/public/admin/test.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Admin Panel - Diagnostic Test</h1>";
echo "<hr>";

// Test 1: Database connection
echo "<h2>1. Database Connection</h2>";
try {
    require_once __DIR__ . '/includes/db.php';
    if ($conn->connect_error) {
        echo "<span style='color:red'>❌ ERROR: " . $conn->connect_error . "</span>";
    } else {
        echo "<span style='color:green'>✓ Database connected: " . $conn->get_server_info() . "</span>";
    }
} catch (Exception $e) {
    echo "<span style='color:red'>❌ ERROR: " . $e->getMessage() . "</span>";
}

// Test 2: Session
echo "<h2>2. Session</h2>";
session_start();
echo "<span style='color:green'>✓ Session started</span>";
if (isset($_SESSION['admin_id'])) {
    echo " (Logged in as: " . htmlspecialchars($_SESSION['admin_name']) . ")";
} else {
    echo " (Not logged in)";
}

// Test 3: File includes
echo "<h2>3. File Includes</h2>";
$files = [
    'includes/db.php' => 'Database wrapper',
    'includes/header.php' => 'Header layout',
    'includes/footer.php' => 'Footer layout',
    'includes/auth.php' => 'Auth utilities',
    'assets/css/admin.css' => 'Admin CSS',
    'assets/js/admin.js' => 'Admin JS',
];

foreach ($files as $file => $desc) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "<span style='color:green'>✓</span> $desc ($file)<br>";
    } else {
        echo "<span style='color:red'>❌</span> $desc ($file) - <b>FILE NOT FOUND</b><br>";
    }
}

// Test 4: Database tables
echo "<h2>4. Database Tables</h2>";
$tables = ['admin', 'mahasiswa', 'mata_kuliah', 'kelas', 'jadwal', 'absensi', 'pengajuan_izin'];
foreach ($tables as $table) {
    $res = $conn->query("SHOW TABLES LIKE '$table'");
    if ($res && $res->num_rows > 0) {
        echo "<span style='color:green'>✓</span> Table: $table<br>";
    } else {
        echo "<span style='color:red'>❌</span> Table: $table - <b>NOT FOUND</b><br>";
    }
}

// Test 5: Admin data
echo "<h2>5. Admin Data</h2>";
$res = $conn->query("SELECT COUNT(*) as cnt FROM admin WHERE deleted_at IS NULL");
if ($res) {
    $row = $res->fetch_assoc();
    $cnt = (int)$row['cnt'];
    if ($cnt > 0) {
        echo "<span style='color:green'>✓</span> Admin records: $cnt<br>";
        $res2 = $conn->query("SELECT username, nama FROM admin WHERE deleted_at IS NULL LIMIT 3");
        echo "<ul>";
        while ($row = $res2->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['username']) . " - " . htmlspecialchars($row['nama']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<span style='color:orange'>⚠</span> No admin records (will use fallback: admin/admin123)<br>";
    }
} else {
    echo "<span style='color:red'>❌</span> Query error: " . $conn->error . "<br>";
}

// Test 6: Quick navigation
echo "<h2>6. Quick Navigation</h2>";
echo "<ul>";
echo "<li><a href='login.php'>Go to Login</a></li>";
echo "<li><a href='index.php'>Go to Dashboard</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p style='font-size:0.9rem;color:#666'>Test completed at " . date('Y-m-d H:i:s') . "</p>";
echo "<p style='font-size:0.9rem;color:#666'><b>Catatan:</b> Hapus file ini (test.php) di production untuk keamanan!</p>";

?>
