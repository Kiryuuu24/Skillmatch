# SkillMatch — Sistem Pencarian Tim Lomba

**SkillMatch** adalah aplikasi web berbasis Laravel untuk mempertemukan mahasiswa Universitas Lampung yang mencari tim lomba dengan ketua tim yang membutuhkan anggota. Sistem menggunakan algoritma **Smart Match** berbasis content-based filtering untuk merekomendasikan tim/kandidat berdasarkan kecocokan skill.

---

## 🎯 Fitur Utama

### Untuk Mahasiswa (Calon Anggota)
- ✅ **Registrasi & Login** dengan validasi password yang kuat
- 👤 **Manajemen Profil** lengkap (NPM, prodi, angkatan, skill, pengalaman, CV, portofolio)
- 🔒 **Pengaturan Privasi** untuk portofolio dan sosial media
- 🟢 **Status Ketersediaan** (Open to Join / Tidak Mencari)
- 🔍 **Pencarian & Filter Tim** berdasarkan kategori, skill, kuota
- 📝 **Apply ke Tim** dengan pesan lamaran
- 🎯 **Smart Match Recommendations** — rekomendasi tim yang cocok dengan skill

### Untuk Ketua Tim
- 📢 **Buat Postingan** dengan detail lomba, skill yang dibutuhkan, kuota, deadline
- 👥 **Cari Kandidat** berdasarkan skill, prodi, angkatan, status Open to Join
- 📥 **Kelola Pengajuan** masuk (terima/tolak pelamar)
- 👁️ **Lihat Profil & Portofolio** pelamar (sesuai pengaturan privasi)
- 🎯 **Rekomendasi Kandidat** yang cocok dengan kebutuhan tim

### Untuk Admin
- 🛠️ **Kelola Akun Pengguna** (view, edit, aktivasi/deaktivasi, hapus)
- 📋 **Moderasi Postingan** (view, edit, sembunyikan, hapus)
- 📊 Dashboard untuk monitoring sistem

---

## 🚀 Teknologi

- **Backend**: Laravel 13.37 (PHP 8.5)
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum (API Token)
- **Testing**: PHPUnit (25 tests, 77 assertions)
- **Code Style**: Laravel Pint

---

## 📦 Instalasi

### 1. Clone Repository
```bash
cd D:\laragon\www
git clone <repository-url> skillmatch_laravel
cd skillmatch_laravel
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
# Copy file .env
cp .env.example .env

# Generate key
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skillmatch_laravel
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database
Buka phpMyAdmin atau MySQL client, lalu jalankan:
```sql
CREATE DATABASE skillmatch_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Jalankan Migration & Seeder
```bash
php artisan migrate:fresh --seed
```

Ini akan membuat:
- ✅ Semua tabel database (users, profiles, skills, posts, applications, dll)
- ✅ 40 skills standar (Laravel, UI/UX, IoT, Machine Learning, dll)
- ✅ Admin default: `admin@skillmatch.id` / `Admin123!`

### 7. Jalankan Server
```bash
php artisan serve
```

Aplikasi berjalan di `http://localhost:8000`

---

## 🧪 Testing

Jalankan semua test:
```bash
php artisan test --compact
```

Jalankan test spesifik:
```bash
php artisan test --filter=AuthTest
php artisan test --filter=PostTest
php artisan test --filter=ApplicationTest
```

**Status Testing**: ✅ 25 tests passed, 77 assertions

---

## 📡 API Endpoints

### Authentication (KF-01)
```
POST   /api/auth/register       - Registrasi pengguna baru
POST   /api/auth/login          - Login (username atau email)
POST   /api/auth/logout         - Logout (protected)
GET    /api/auth/me             - Get user info (protected)
```

### Profile Management (KF-02, KF-02a, KF-03)
```
GET    /api/profile             - Get own profile
PUT    /api/profile             - Update profile
PUT    /api/profile/skills      - Update user skills
PUT    /api/profile/status      - Update status ketersediaan
PUT    /api/profile/privacy     - Update privacy settings
GET    /api/profile/{userId}    - View public profile
GET    /api/skills              - Get all skills catalog
```

### Posts (KF-04, KF-05, KF-06)
```
GET    /api/posts                        - List posts (with filters)
POST   /api/posts                        - Create post
GET    /api/posts/{id}                   - Get post detail
PUT    /api/posts/{id}                   - Update post (ketua only)
DELETE /api/posts/{id}                   - Delete post (ketua only)
GET    /api/posts/search-candidates      - Search candidates (KF-05)
```

### Applications (KF-07, KF-08)
```
GET    /api/applications         - List applications (role=pelamar|ketua, tab=menunggu|diterima|ditolak)
POST   /api/applications         - Submit application
GET    /api/applications/{id}    - View application detail
PUT    /api/applications/{id}    - Accept/Reject (ketua only)
DELETE /api/applications/{id}    - Withdraw application (pelamar only)
```

### Smart Match (KF-09)
```
GET    /api/smart-match/recommendations              - Get recommended posts for user
GET    /api/smart-match/candidates/{postId}          - Get recommended candidates for post
```

### Admin (KF-10, KF-11)
```
# User Management
GET    /api/admin/users              - List users
GET    /api/admin/users/{id}         - User detail
PUT    /api/admin/users/{id}         - Update user
POST   /api/admin/users/{id}/activate   - Activate account
POST   /api/admin/users/{id}/deactivate - Deactivate account
DELETE /api/admin/users/{id}         - Delete user

# Post Management
GET    /api/admin/posts              - List all posts
GET    /api/admin/posts/{id}         - Post detail
PUT    /api/admin/posts/{id}         - Update post
POST   /api/admin/posts/{id}/hide    - Hide post
POST   /api/admin/posts/{id}/unhide  - Unhide post
DELETE /api/admin/posts/{id}         - Delete post
```

---

## 🔐 Authentication

API menggunakan **Laravel Sanctum** dengan Bearer Token.

### Cara Pakai:
1. Login/Register untuk mendapatkan token
2. Kirim token di header setiap request:
```http
Authorization: Bearer {your-token-here}
```

### Contoh dengan cURL:
```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"login":"admin@skillmatch.id","password":"Admin123!"}'

# Get Profile (dengan token)
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🎯 Algoritma Smart Match (KF-09)

Sistem menggunakan **content-based filtering** untuk mencocokkan:

### Skor Matching:
1. **Skill Match** (bobot utama):
   - Skill wajib = bobot 2
   - Skill opsional = bobot 1
   - Skor = (total bobot skill yang cocok) / (total bobot skill dibutuhkan)

2. **Kategori Bonus** (+0.1):
   - Jika kategori lomba sesuai minat pengguna

3. **Filter**:
   - Hanya postingan aktif, belum penuh, belum lewat deadline
   - Hanya kandidat dengan status "Open to Join"

### Contoh:
```
Post membutuhkan:
- Laravel (wajib) → bobot 2
- Vue.js (wajib) → bobot 2
- UI/UX (opsional) → bobot 1
Total: 5

User punya:
- Laravel ✅
- Vue.js ✅
Cocok: 4/5 = 80% match score
```

---

## 📊 Database Schema

### Users
- id, name, username, email, password
- role (mahasiswa/admin)
- status_akun (aktif/nonaktif)

### Profiles (1:1 dengan Users)
- user_id, npm, prodi, angkatan
- pengalaman_lomba, minat_bidang_lomba
- link_portofolio, cv, link_sosmed
- is_open_to_join, is_portfolio_public, is_sosmed_public

### Skills (katalog)
- id, nama_skill, kategori_skill

### User_Skills (pivot Many-to-Many)
- user_id, skill_id, tingkat_keahlian (pemula/menengah/mahir)

### Posts
- id, ketua_id, nama_lomba, kategori_lomba
- jumlah_dibutuhkan, jumlah_terisi
- deadline_daftar, link_sosmed, deskripsi_syarat
- status_postingan (aktif/penuh/ditutup/disembunyikan)

### Post_Skill_Reqs (pivot)
- post_id, skill_id, prioritas (wajib/opsional)

### Applications
- id, post_id, pelamar_id, pesan_lamaran
- status_pengajuan (menunggu/diterima/ditolak)

---

## 👥 Akun Default

### Admin
- **Email**: `dapplenathea@gmail.com`
- **Password**: `Admin123!`
- Akses: Dashboard admin, kelola users & posts

---

## 🔒 Keamanan (KNF-01)

✅ **Password hashing** dengan bcrypt  
✅ **Validasi password**: minimal 8 karakter, 1 huruf kapital, 1 angka, 1 karakter khusus  
✅ **SQL Injection protection** via Eloquent ORM  
✅ **XSS protection** via Laravel sanitization  
✅ **CSRF protection** via Laravel middleware  
✅ **Privacy controls** untuk portofolio & sosmed  
✅ **Authorization checks** di setiap endpoint

---

## 🎨 Validasi & Aturan

### Password (KF-01):
- Minimal 8 karakter
- 1 huruf kapital
- 1 angka
- 1 karakter khusus (@$!%*?&)

### Postingan (KF-04):
- Nama lomba wajib
- Kategori lomba wajib
- Minimal 1 skill requirement
- Link kontak wajib
- Deadline harus setelah hari ini

### Apply (KF-07):
- Tidak bisa apply ke postingan sendiri
- Tidak bisa apply dua kali ke postingan yang sama
- Tidak bisa apply jika postingan sudah penuh/lewat deadline

---

## 📈 Requirement Coverage

| KF-ID | Requirement | Status |
|-------|-------------|--------|
| KF-01 | Registrasi & Login | ✅ (9 tests) |
| KF-02 | Manajemen Profil | ✅ |
| KF-02a | Pengaturan Privasi | ✅ |
| KF-03 | Status Ketersediaan | ✅ |
| KF-04 | Buat Postingan | ✅ (tested) |
| KF-05 | Cari Kandidat | ✅ (tested) |
| KF-06 | Cari & Filter Postingan | ✅ (tested) |
| KF-07 | Pengajuan Bergabung | ✅ (7 tests) |
| KF-08 | Kelola Pengajuan | ✅ (5 tests) |
| KF-09 | Smart Match | ✅ |
| KF-10 | Kelola Akun (Admin) | ✅ |
| KF-11 | Kelola Postingan (Admin) | ✅ (tested) |

**Total**: 12/12 fitur selesai ✅

---

## 🛠️ Development Commands

```bash
# Jalankan server
php artisan serve

# Jalankan migration
php artisan migrate

# Reset database + seed
php artisan migrate:fresh --seed

# Format code
vendor/bin/pint

# Run tests
php artisan test --compact

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# List routes
php artisan route:list
```

---

## 📝 TODO / Future Enhancements

- [ ] Chat/messaging in-app (saat ini via link sosmed)
- [ ] Login via Google OAuth
- [ ] Email notifications untuk status pengajuan
- [ ] Export data mahasiswa (admin)
- [ ] Filter berdasarkan pengalaman lomba
- [ ] Rating/review system untuk anggota tim
- [ ] Mobile app (Flutter/React Native)

---

## 📞 Kontak & Support

- **Developer**: Kiryuu
- **Tahun**: 2026
- **Email**: dapplenathea@gmail.com

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademik Universitas Lampung.

---

**Built with ❤️ using Laravel 13**
