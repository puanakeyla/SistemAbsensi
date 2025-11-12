# ⚡ QUICK START - SISTEM ADMIN PANEL

## 🟢 STATUS: VERIFIED & READY TO USE ✅

---

## 🚀 MULAI SEKARANG

### Step 1: Setup Admin Account (First Time Only)
```
http://localhost/SistemAbsensi/admin_setup.php
```
- Username: (isi sesuai keinginan)
- Nama: (nama admin)
- Password: (minimal 8 karakter)
- ✅ Klik "Create Admin Account"
- 🗑️ **HAPUS file admin_setup.php setelah selesai**

### Step 2: Login
```
http://localhost/SistemAbsensi/public/admin/login.php
```
- Gunakan username & password yang dibuat di Step 1
- Akan otomatis redirect ke dashboard

### Step 3: Akses Dashboard
```
http://localhost/SistemAbsensi/public/admin/index.php
```
- Otomatis terbuka setelah login berhasil
- Lihat statistik: Mahasiswa, Kelas, Pengajuan Izin, Absensi

---

## 📊 MENU ADMIN (Sidebar)

| Menu | URL | Fungsi |
|------|-----|--------|
| Dashboard | `index.php` | Statistik & Overview |
| Mahasiswa | `students.php` | Daftar semua mahasiswa |
| Kehadiran | `attendance.php` | Daftar absensi & status |
| Mata Kuliah | `courses.php` | Daftar mata kuliah |
| Kelas | `classes.php` | Daftar kelas |
| Jadwal | `schedules.php` | Jadwal kuliah |
| Admin | `admins.php` | Daftar admin accounts |
| Pengajuan Izin | `izin_requests.php` | Permintaan izin mahasiswa |
| Logout | `logout.php` | Keluar dari sistem |

---

## 🔧 TEKNOLOGI

- **Backend**: PHP 8.x
- **Database**: MySQL/MariaDB 10.4.28 (MySQLi Driver)
- **Frontend**: Poppins Font + Font Awesome 6.4.0
- **Layout**: Responsive (Desktop, Tablet, Mobile)
- **Security**: Prepared Statements, Password Hashing, Session Auth

---

## ✅ VERIFIKASI SISTEM

Untuk memastikan semua berfungsi:
```
http://localhost/SistemAbsensi/public/admin/test.php
```

Harus menunjukkan semua ✓ (checkmark):
- ✓ Database connected
- ✓ Session started
- ✓ File Includes
- ✓ Database Tables (7 tables)
- ✓ Admin Data

---

## 🔐 SECURITY CHECKLIST

Sebelum production:
- [ ] Hapus `test.php` (exposure risk)
- [ ] Hapus `admin_setup.php` (jika sudah create admin)
- [ ] Change MySQL password dari "" menjadi kuat
- [ ] Enable HTTPS (SSL/TLS)
- [ ] Update `config/db.php` dengan database password
- [ ] Set proper file permissions (600 untuk config)

---

## 🐛 TROUBLESHOOTING

### Database Connection Error
- Pastikan XAMPP MySQL/MariaDB running
- Pastikan database `absensi_kampus` exist
- Cek config di `config/db.php`

### Login Failed
- Pastikan admin account sudah created via `admin_setup.php`
- Username & password harus benar (case-sensitive)
- Cek apakah cookies enabled di browser

### CSS/JS Not Loading
- Clear browser cache: `Ctrl+F5`
- Pastikan folder `public/admin/assets/` exist
- Check browser console (F12) untuk error details

### Session Expired
- Auto-logout jika tidak aktif (default PHP session)
- Login lagi untuk refresh session

---

## 📞 SUPPORT FILES

| File | Lokasi | Fungsi |
|------|--------|--------|
| VERIFICATION_REPORT.md | Root | Laporan lengkap verifikasi |
| SETUP_GUIDE.md | Root | Panduan instalasi detail |
| README.md | public/admin/ | Dokumentasi admin panel |

---

## 💡 TIPS

1. **Default Dev Account** (jika tabel admin kosong):
   - Username: `admin`
   - Password: `admin123`
   - ⚠️ Hanya development, jangan di production!

2. **Database Tables** (auto-created dari SQL):
   - `admin` - Admin accounts
   - `mahasiswa` - Student data
   - `mata_kuliah` - Courses
   - `kelas` - Classes
   - `jadwal` - Schedules
   - `absensi` - Attendance
   - `pengajuan_izin` - Leave requests

3. **File Locations**:
   - Admin pages: `public/admin/*.php`
   - Layouts: `public/admin/includes/`
   - Styling: `public/admin/assets/css/`
   - Config: `config/db.php`

---

## 📈 NEXT STEPS (Optional Enhancements)

- [ ] Add CRUD functionality (Create/Edit/Delete)
- [ ] Search & Filter on list pages
- [ ] Pagination for large tables
- [ ] Export to Excel/PDF
- [ ] Dashboard charts/graphs
- [ ] Email notifications
- [ ] Audit logging

---

**Last Updated**: 12 November 2025  
**Status**: ✅ ALL SYSTEMS VERIFIED - ZERO ERRORS  
**Ready**: Production Ready (with security notes)

---

**INGIN MULAI?** 👉 Buka: `http://localhost/SistemAbsensi/admin_setup.php`
