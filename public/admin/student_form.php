<?php
require_once __DIR__ . '/includes/header.php';

$edit_mode = isset($_GET['id']);
$mahasiswa = null;

if ($edit_mode) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM mahasiswa WHERE id = $id AND deleted_at IS NULL");
    $mahasiswa = $result->fetch_assoc();
    if (!$mahasiswa) {
        header('Location: students.php');
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $conn->real_escape_string($_POST['nim']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $no_telp = $conn->real_escape_string($_POST['no_telp']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $status = $_POST['status'];
    
    if ($edit_mode) {
        // Update
        $id = (int)$_POST['id'];
        $sql = "UPDATE mahasiswa SET 
                nim = '$nim',
                nama = '$nama',
                email = '$email',
                no_telp = '$no_telp',
                alamat = '$alamat',
                tanggal_lahir = '$tanggal_lahir',
                jenis_kelamin = '$jenis_kelamin',
                status = '$status'
                WHERE id = $id";
        
        if ($conn->query($sql)) {
            header('Location: students.php?msg=updated');
            exit;
        }
    } else {
        // Insert
        $password = password_hash('12345', PASSWORD_DEFAULT); // Default password
        $sql = "INSERT INTO mahasiswa (nim, nama, email, password, no_telp, alamat, tanggal_lahir, jenis_kelamin, status) 
                VALUES ('$nim', '$nama', '$email', '$password', '$no_telp', '$alamat', '$tanggal_lahir', '$jenis_kelamin', '$status')";
        
        if ($conn->query($sql)) {
            header('Location: students.php?msg=added');
            exit;
        }
    }
}

?>

<h1><i class="fas fa-user-edit"></i> <?php echo $edit_mode ? 'Edit' : 'Tambah'; ?> Mahasiswa</h1>

<div class="card">
    <div style="margin-bottom: 20px;">
        <h2><i class="fas fa-wpforms"></i> Form Data Mahasiswa</h2>
    </div>
    
    <form method="POST" style="max-width: 800px;">
        <?php if ($edit_mode): ?>
        <input type="hidden" name="id" value="<?php echo $mahasiswa['id']; ?>">
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">
                    <i class="fas fa-id-card"></i> NIM <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="nim" required 
                    value="<?php echo $mahasiswa['nim'] ?? ''; ?>"
                    placeholder="Contoh: 2021001"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">
                    <i class="fas fa-user"></i> Nama Lengkap <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="nama" required 
                    value="<?php echo $mahasiswa['nama'] ?? ''; ?>"
                    placeholder="Nama lengkap mahasiswa"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">
                    <i class="fas fa-envelope"></i> Email <span style="color: var(--danger);">*</span>
                </label>
                <input type="email" name="email" required 
                    value="<?php echo $mahasiswa['email'] ?? ''; ?>"
                    placeholder="email@student.ac.id"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">
                    <i class="fas fa-phone"></i> No. Telepon
                </label>
                <input type="text" name="no_telp" 
                    value="<?php echo $mahasiswa['no_telp'] ?? ''; ?>"
                    placeholder="081234567890"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">
                <i class="fas fa-map-marker-alt"></i> Alamat
            </label>
            <textarea name="alamat" rows="3" 
                placeholder="Alamat lengkap mahasiswa"
                style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; font-family: inherit;"><?php echo $mahasiswa['alamat'] ?? ''; ?></textarea>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">
                    <i class="fas fa-calendar"></i> Tanggal Lahir
                </label>
                <input type="date" name="tanggal_lahir" 
                    value="<?php echo $mahasiswa['tanggal_lahir'] ?? ''; ?>"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">
                    <i class="fas fa-venus-mars"></i> Jenis Kelamin
                </label>
                <select name="jenis_kelamin" 
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="L" <?php echo (isset($mahasiswa['jenis_kelamin']) && $mahasiswa['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="P" <?php echo (isset($mahasiswa['jenis_kelamin']) && $mahasiswa['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">
                    <i class="fas fa-toggle-on"></i> Status
                </label>
                <select name="status" 
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="aktif" <?php echo (!isset($mahasiswa['status']) || $mahasiswa['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="nonaktif" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] == 'nonaktif') ? 'selected' : ''; ?>>Non-Aktif</option>
                    <option value="cuti" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] == 'cuti') ? 'selected' : ''; ?>>Cuti</option>
                </select>
            </div>
        </div>
        
        <?php if (!$edit_mode): ?>
        <div style="background: #e7f3ff; border-left: 4px solid var(--secondary); padding: 12px; margin-bottom: 20px; border-radius: 4px;">
            <i class="fas fa-info-circle"></i> <strong>Info:</strong> Password default untuk mahasiswa baru adalah <code style="background: #fff; padding: 2px 6px; border-radius: 3px;">12345</code>
        </div>
        <?php endif; ?>
        
        <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #eee;">
            <button type="submit" class="btn" style="background: var(--success);">
                <i class="fas fa-save"></i> Simpan Data
            </button>
            <a href="students.php" class="btn" style="background: var(--gray);">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
