# SETUP & TESTING GUIDE - Admin Panel

## ✅ Verifikasi Syntax
Semua file PHP sudah di-check dan tidak ada syntax error:
- ✓ `public/admin/login.php` — No syntax errors
- ✓ `public/admin/index.php` — No syntax errors
- ✓ `public/admin/students.php` — No syntax errors
- ✓ `public/admin/attendance.php` — No syntax errors
- ✓ `public/admin/includes/header.php` — No syntax errors
- ✓ `admin_setup.php` — No syntax errors

## 📁 File Structure

```
SistemAbsensi/
├── config/
│   └── db.php                          # Database connection
├── public/
│   └── admin/
│       ├── login.php                   # Login form (standalone)
│       ├── logout.php                  # Logout & clear session
│       ├── test.php                    # Diagnostic test page
│       ├── index.php                   # Dashboard
│       ├── students.php                # Mahasiswa list
│       ├── attendance.php              # Absensi list
│       ├── admins.php                  # Admin list
│       ├── courses.php                 # Mata kuliah list
│       ├── classes.php                 # Kelas list
│       ├── schedules.php               # Jadwal list
│       ├── izin_requests.php           # Pengajuan izin list
│       ├── README.md                   # Documentation
│       ├── includes/
│       │   ├── header.php              # HTML head + sidebar + auth check
│       │   ├── footer.php              # HTML close + script import
│       │   ├── db.php                  # Database wrapper
│       │   └── auth.php                # Auth utilities (deprecated)
│       └── assets/
│           ├── css/admin.css           # Styling (sidebar, cards, responsive)
│           └── js/admin.js             # JavaScript
└── admin_setup.php                     # Admin account setup script
```

## 🚀 Setup Instructions

### 1. Database Setup
Pastikan MySQL sudah berjalan dan database `absensi_kampus` sudah ada:

```bash
# Import database (if not already done)
mysql -u root absensi_kampus < database_absensi.sql
```

### 2. Create Admin Account
Ada 2 cara:

#### Option A: Setup Script (Recommended)
1. Buka browser: http://localhost/SistemAbsensi/admin_setup.php
2. Isi form:
   - Username: `admin` (atau nama lain)
   - Nama Lengkap: `Administrator`
   - Email: `admin@sistem.local`
   - Password: `YourSecurePassword123`
   - Konfirmasi: `YourSecurePassword123`
3. Klik "Buat Akun Admin"
4. **HAPUS file `admin_setup.php` setelah selesai** untuk keamanan

#### Option B: Manual SQL (Alternative)
Jalankan di phpMyAdmin atau MySQL CLI:

```sql
-- Generate hash di PHP dulu: php -r "echo password_hash('YourSecurePassword123', PASSWORD_DEFAULT);"
-- Ganti $2y$10$... dengan hasil dari command di atas

INSERT INTO admin (username, nama, email, password, created_at)
VALUES ('admin', 'Administrator', 'admin@sistem.local', '$2y$10$...hash_here...', NOW());
```

#### Option C: Fallback Dev (Testing Only)
Jika tabel `admin` kosong, gunakan:
- Username: `admin`
- Password: `admin123`

**⚠ Warning**: Fallback ini hanya untuk testing, bukan production!

### 3. Login & Test
1. Buka: http://localhost/SistemAbsensi/public/admin/login.php
2. Masukkan kredensial (dari step 2)
3. Klik "Masuk"
4. Seharusnya masuk ke dashboard (`/public/admin/index.php`)

### 4. Verification
Run diagnostic test:
1. Buka: http://localhost/SistemAbsensi/public/admin/test.php
2. Cek semua item bertanda ✓ (hijau)
3. Jika ada ❌ (merah), lihat troubleshooting di bawah

## 🧪 Testing Checklist

- [ ] Login page loads tanpa error: http://localhost/SistemAbsensi/public/admin/login.php
- [ ] Login dengan fallback (admin/admin123) berhasil
- [ ] Dashboard tampil dengan statistik
- [ ] Sidebar navigation berfungsi
- [ ] Klik "Mahasiswa" → list mahasiswa tampil
- [ ] Klik "Absensi" → list absensi tampil
- [ ] Klik "Pengajuan Izin" → list izin tampil
- [ ] Logout berfungsi (redirect ke login)
- [ ] Test page menunjukkan semua ✓: http://localhost/SistemAbsensi/public/admin/test.php

## 🔧 Troubleshooting

### Error: "Koneksi gagal"
**Penyebab**: Database tidak berjalan atau kredensial `config/db.php` salah
**Solusi**:
1. Pastikan MySQL berjalan (XAMPP control panel)
2. Cek `config/db.php`:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";  // Sesuaikan dengan setting lokal
   $db = "absensi_kampus";
   ```
3. Jalankan: `mysql -u root -e "SHOW DATABASES;"` di terminal

### Error: "Blank page" atau whitespace
**Penyebab**: Output sebelum session/header start
**Solusi**:
1. Cek tidak ada `echo`, `var_dump`, atau whitespace di awal file
2. Pastikan file tidak punya BOM (Byte Order Mark)
3. Edit dengan UTF-8 tanpa BOM

### Error: "Session not saving" atau "Redirect loop"
**Penyebab**: Session path tidak writable
**Solusi**:
1. Check folder `/tmp` atau session path di `php.ini`
2. Pastikan folder writable: `chmod 777 /tmp` (Linux)
3. Cek di phpinfo: `<?php phpinfo(); ?>`

### Error: "Header already sent"
**Penyebab**: Output dikirim sebelum `header()` call
**Solusi**:
1. Pastikan file `.php` dimulai dengan `<?php` tanpa newline sebelumnya
2. Tidak ada `echo` atau whitespace sebelum include
3. Gunakan output buffering jika perlu: `ob_start();` di awal

### Halaman kosong tanpa error
**Penyebab**: Auth redirect ke login.php
**Solusi**:
1. Pastikan sudah login di `public/admin/login.php` dulu
2. Cek session variable: buat file test:
   ```php
   <?php session_start(); var_dump($_SESSION); ?>
   ```
3. Lihat troubleshooting "Session not saving" di atas

## 🔐 Security Checklist

- [ ] Setup akun admin via `admin_setup.php`
- [ ] Hapus file `admin_setup.php`
- [ ] Hapus fallback dev di `login.php` (baris ~34)
- [ ] Gunakan HTTPS di production
- [ ] Backup database sebelum deploy
- [ ] Atur file permissions (644 files, 755 dirs)
- [ ] Test di incognito/private mode (session baru)

## 📝 Config Files to Check

**`config/db.php`** (ROOT):
```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "absensi_kampus";
```

**`public/admin/includes/db.php`**:
```php
<?php
require_once __DIR__ . '/../../../config/db.php';
// $conn object kini tersedia
?>
```

## 🎯 Next Steps After Setup

1. **Create more admin accounts** (if needed) → use admin_setup.php again
2. **Implement CRUD** for Mahasiswa & Admin
3. **Add search/filter** to list pages
4. **Implement CSRF token** for forms
5. **Setup email notifications**
6. **Add audit logging** untuk admin actions

## 📞 Support

Jika ada masalah:
1. Buka: http://localhost/SistemAbsensi/public/admin/test.php (diagnostic)
2. Cek error logs di: XAMPP/apache/logs/ atau XAMPP/mysql/data/
3. Inspect browser dev tools (F12) → Console tab
4. Check browser network tab untuk redirect/error responses

---

**Generated**: November 2025
**Status**: ✅ All files verified, no syntax errors
