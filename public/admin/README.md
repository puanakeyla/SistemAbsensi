# Admin Panel - Sistem Absensi

## Akses & Login

URL: `http://localhost/SistemAbsensi/public/admin/login.php`

### Setup Awal

#### Opsi 1: Menggunakan Setup Script (Recommended)
1. Buka: `http://localhost/SistemAbsensi/admin_setup.php`
2. Isi form untuk membuat akun admin pertama
3. Klik "Buat Akun Admin"
4. **PENTING**: Hapus file `admin_setup.php` setelah selesai untuk keamanan

#### Opsi 2: Fallback Development (Quick Test)
Jika tabel `admin` kosong di database, gunakan kredensial fallback:
- Username: `admin`
- Password: `admin123`

**Catatan**: Fallback ini hanya untuk testing/development. Ganti ke akun real setelah setup.

## Struktur File

```
public/admin/
├── login.php                 # Halaman login (standalone)
├── logout.php                # Logout & destroy session
├── index.php                 # Dashboard (statistik, quick links)
├── students.php              # Daftar mahasiswa
├── attendance.php            # Rekap absensi
├── admins.php                # Daftar admin
├── courses.php               # Daftar mata kuliah
├── classes.php               # Daftar kelas
├── schedules.php             # Daftar jadwal
├── izin_requests.php         # Daftar pengajuan izin
├── includes/
│   ├── header.php           # Layout sidebar + topbar + auth check
│   ├── footer.php           # Closing tags + script import
│   ├── db.php               # Wrapper ke config/db.php
│   └── auth.php             # Auth utilities (deprecated, header.php handle it)
└── assets/
    ├── css/admin.css        # Styling (sidebar, topbar, cards, responsive)
    └── js/admin.js          # JavaScript (placeholder)
```

## Database Requirements

Pastikan sudah import `database_absensi.sql` ke MySQL database `absensi_kampus`:

```bash
mysql -u root absensi_kampus < database_absensi.sql
```

Tabel yang digunakan:
- `admin` — akun admin (id, username, password hash, nama, email, created_at, deleted_at)
- `mahasiswa` — data mahasiswa
- `mata_kuliah` — data mata kuliah
- `kelas` — data kelas
- `jadwal` — jadwal kelas
- `absensi` — data absensi
- `pengajuan_izin` — pengajuan izin dari mahasiswa

## Fitur

- ✅ Login dengan session validation
- ✅ Password hashing (password_hash, password_verify)
- ✅ Sidebar navigation
- ✅ Dashboard dengan statistik (total mahasiswa, kelas, pending izin)
- ✅ Halaman daftar: Mahasiswa, Absensi, Admin, Mata Kuliah, Kelas, Jadwal, Pengajuan Izin
- ✅ Responsive design (desktop, tablet)
- ✅ Logout

## Keamanan

⚠ **Penting untuk Production:**
1. **Hapus fallback dev** di `login.php` (baris ~34-40) setelah setup awal
2. **Hapus file `admin_setup.php`** setelah membuat akun admin
3. Selalu gunakan **HTTPS** di production
4. Gunakan **prepared statements** (sudah implemented)
5. Jangan hardcode password, gunakan `.env` atau config yang aman
6. Set permissions folder admin (640, 750, etc.) di server

## Koneksi Database

File: `config/db.php` (di root project)

```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "absensi_kampus";
```

Semua halaman admin include wrapper `includes/db.php` → `config/db.php`, jadi koneksi $conn otomatis tersedia.

## Development vs Production

### Development (Current)
- Fallback login: `admin / admin123` (jika admin kosong)
- Setup script: `admin_setup.php` (untuk membuat akun)

### Production Checklist
- [ ] Setup akun admin real via `admin_setup.php`
- [ ] Hapus `admin_setup.php`
- [ ] Hapus fallback dev di `login.php`
- [ ] Aktifkan HTTPS
- [ ] Set proper file permissions (644 files, 755 dirs)
- [ ] Backup database

## Troubleshooting

### Error: "Koneksi gagal" atau blank page
1. Cek MySQL sudah berjalan
2. Cek database `absensi_kampus` sudah ada
3. Cek `config/db.php` sesuai setting lokal

### Error: "Header already sent"
- Pastikan tidak ada output/echo sebelum header.php include
- Cek tidak ada BOM di awal file PHP

### Error: Redirect loop atau blank login page
- Cek session.save_path writable
- Cek folder `includes/` readable
- Buat test file: `<?php echo "OK"; ?>` di `public/admin/test.php`

## Next Steps

- Tambah CRUD (create/edit/delete) untuk Mahasiswa & Admin
- Tambah filter/search di halaman daftar
- Implementasi CSRF token
- Audit trail (log admin activities)
- Email notifications

---

**Last updated**: November 2025
**Author**: Admin Panel Generator

