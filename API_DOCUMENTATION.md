# SkillMatch API Documentation

## Base URL
```
http://localhost:8000/api
```

## Response Format

All API responses follow this format:

### Success Response
```json
{
  "success": true,
  "message": "Success message (optional)",
  "data": {
    // Response data
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error detail"]
  }
}
```

## Authentication

Most endpoints require authentication using Bearer Token.

### Headers
```
Authorization: Bearer {your-token-here}
Content-Type: application/json
Accept: application/json
```

---

## 1. Authentication Endpoints

### 1.1 Register
**POST** `/auth/register`

Request:
```json
{
  "name": "John Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!"
}
```

Response (201):
```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "role": "mahasiswa"
    },
    "token": "1|abc123..."
  }
}
```

### 1.2 Login
**POST** `/auth/login`

Request:
```json
{
  "login": "johndoe",  // atau email
  "password": "Password123!"
}
```

Response (200):
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "role": "mahasiswa"
    },
    "token": "2|xyz789...",
    "redirect": "/beranda"
  }
}
```

### 1.3 Logout
**POST** `/auth/logout`

Headers: `Authorization: Bearer {token}`

Response (200):
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

### 1.4 Get Current User
**GET** `/auth/me`

Headers: `Authorization: Bearer {token}`

Response (200):
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "role": "mahasiswa",
      "profile": {
        "npm": "2011521001",
        "prodi": "Teknik Informatika",
        "angkatan": "2020",
        "is_open_to_join": true
      }
    }
  }
}
```

---

## 2. Profile Endpoints

### 2.1 Get Own Profile
**GET** `/profile`

Headers: `Authorization: Bearer {token}`

Response (200):
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "profile": {
        "npm": "2011521001",
        "prodi": "Teknik Informatika",
        "angkatan": "2020",
        "pengalaman_lomba": "3x Hackathon",
        "minat_bidang_lomba": "Web Development, IoT",
        "link_portofolio": "https://github.com/johndoe",
        "cv": "cvs/john_cv.pdf",
        "link_sosmed": "https://instagram.com/johndoe",
        "is_open_to_join": true,
        "is_portfolio_public": true,
        "is_sosmed_public": true
      },
      "skills": [
        {
          "id": 1,
          "skill_id": 1,
          "tingkat_keahlian": "mahir",
          "skill": {
            "id": 1,
            "nama_skill": "Laravel",
            "kategori_skill": "Pemrograman"
          }
        }
      ]
    }
  }
}
```

### 2.2 Update Profile
**PUT** `/profile`

Headers: `Authorization: Bearer {token}`

Request (multipart/form-data for CV upload):
```json
{
  "name": "John Doe Updated",
  "npm": "2011521001",
  "prodi": "Teknik Informatika",
  "angkatan": "2020",
  "pengalaman_lomba": "3x Hackathon Winner",
  "minat_bidang_lomba": "Web Development, IoT, AI",
  "link_portofolio": "https://github.com/johndoe",
  "link_sosmed": "https://instagram.com/johndoe",
  "cv": "<file>"
}
```

Response (200):
```json
{
  "success": true,
  "message": "Profil berhasil diperbarui",
  "data": {
    "user": { /* updated user data */ }
  }
}
```

### 2.3 Update Skills
**PUT** `/profile/skills`

Headers: `Authorization: Bearer {token}`

Request:
```json
{
  "skills": [
    {
      "skill_id": 1,
      "tingkat_keahlian": "mahir"
    },
    {
      "skill_id": 13,
      "tingkat_keahlian": "menengah"
    }
  ]
}
```

Response (200):
```json
{
  "success": true,
  "message": "Skill berhasil diperbarui",
  "data": {
    "skills": [ /* updated skills */ ]
  }
}
```

### 2.4 Update Status
**PUT** `/profile/status`

Request:
```json
{
  "is_open_to_join": true
}
```

Response (200):
```json
{
  "success": true,
  "message": "Status ketersediaan berhasil diperbarui",
  "data": {
    "profile": { /* updated profile */ }
  }
}
```

### 2.5 Update Privacy
**PUT** `/profile/privacy`

Request:
```json
{
  "is_portfolio_public": true,
  "is_sosmed_public": false
}
```

Response (200):
```json
{
  "success": true,
  "message": "Pengaturan privasi berhasil diperbarui"
}
```

### 2.6 View Public Profile
**GET** `/profile/{userId}`

Response (200):
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 2,
      "name": "Jane Smith",
      "profile": {
        "prodi": "Teknik Informatika",
        "angkatan": "2021",
        "link_portofolio": null,  // hidden if is_portfolio_public = false
        "link_sosmed": null       // hidden if is_sosmed_public = false
      }
    }
  }
}
```

---

## 3. Skills Endpoints

### 3.1 Get All Skills
**GET** `/skills`

Query params:
- `kategori` (optional): Filter by category

Response (200):
```json
{
  "success": true,
  "data": {
    "skills": [
      {
        "id": 1,
        "nama_skill": "Laravel",
        "kategori_skill": "Pemrograman"
      },
      {
        "id": 13,
        "nama_skill": "UI/UX Design",
        "kategori_skill": "Desain"
      }
    ]
  }
}
```

---

## 4. Posts Endpoints

### 4.1 List Posts
**GET** `/posts`

Query params:
- `keyword` (optional): Search in nama_lomba, kategori, deskripsi
- `kategori` (optional): Filter by kategori_lomba
- `skill_id` (optional): Filter by required skill
- `tersedia` (optional): true = only available posts
- `per_page` (optional): Pagination size (default: 15)

Response (200):
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "nama_lomba": "Hackathon Unila 2026",
        "kategori_lomba": "Pemrograman",
        "jumlah_dibutuhkan": 3,
        "jumlah_terisi": 1,
        "deadline_daftar": "2026-11-30",
        "link_sosmed": "https://wa.me/1234",
        "status_postingan": "aktif",
        "ketua": {
          "id": 2,
          "name": "Team Leader"
        },
        "skill_requirements": [
          {
            "skill": {
              "nama_skill": "Laravel",
              "kategori_skill": "Pemrograman"
            },
            "prioritas": "wajib"
          }
        ]
      }
    ],
    "total": 10
  }
}
```

### 4.2 Create Post
**POST** `/posts`

Headers: `Authorization: Bearer {token}`

Request:
```json
{
  "nama_lomba": "Hackathon Unila 2026",
  "kategori_lomba": "Pemrograman",
  "jumlah_dibutuhkan": 3,
  "deadline_daftar": "2026-12-31",
  "link_sosmed": "https://wa.me/628123456789",
  "deskripsi_syarat": "Mahasiswa aktif, berpengalaman",
  "skills": [
    {
      "skill_id": 1,
      "prioritas": "wajib"
    },
    {
      "skill_id": 4,
      "prioritas": "opsional"
    }
  ]
}
```

Response (201):
```json
{
  "success": true,
  "message": "Postingan berhasil dibuat dan dipublikasikan",
  "data": {
    "post": { /* created post */ }
  }
}
```

### 4.3 Get Post Detail
**GET** `/posts/{id}`

Response (200):
```json
{
  "success": true,
  "data": {
    "post": {
      "id": 1,
      "nama_lomba": "Hackathon Unila 2026",
      "kategori_lomba": "Pemrograman",
      "jumlah_dibutuhkan": 3,
      "jumlah_terisi": 1,
      "deadline_daftar": "2026-12-31",
      "deskripsi_syarat": "Mahasiswa aktif...",
      "ketua": { /* ketua data */ },
      "skill_requirements": [ /* required skills */ ]
    }
  }
}
```

### 4.4 Search Candidates
**GET** `/posts/search-candidates`

Query params:
- `keyword` (optional): Search by name or prodi
- `skill_id` (optional): Filter by skill
- `prodi` (optional): Filter by prodi
- `angkatan` (optional): Filter by angkatan
- `open_to_join` (optional): true = only Open to Join users (default: true)

Response (200):
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 3,
        "name": "Candidate Name",
        "profile": {
          "prodi": "Teknik Informatika",
          "angkatan": "2020",
          "is_open_to_join": true
        },
        "skills": [ /* user skills */ ]
      }
    ]
  }
}
```

---

## 5. Applications Endpoints

### 5.1 List Applications
**GET** `/applications`

Headers: `Authorization: Bearer {token}`

Query params:
- `role`: `pelamar` or `ketua`
- `tab`: `menunggu`, `diterima`, or `ditolak`

Response (200):
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "post_id": 1,
        "pelamar_id": 2,
        "pesan_lamaran": "Saya tertarik...",
        "status_pengajuan": "menunggu",
        "post": { /* post data */ },
        "pelamar": { /* applicant data */ }
      }
    ]
  }
}
```

### 5.2 Submit Application
**POST** `/applications`

Headers: `Authorization: Bearer {token}`

Request:
```json
{
  "post_id": 1,
  "pesan_lamaran": "Saya tertarik bergabung dengan tim ini..."
}
```

Response (201):
```json
{
  "success": true,
  "message": "Pengajuan berhasil dikirim",
  "data": {
    "application": { /* created application */ }
  }
}
```

### 5.3 Update Application Status (Ketua Only)
**PUT** `/applications/{id}`

Headers: `Authorization: Bearer {token}`

Request:
```json
{
  "status_pengajuan": "diterima"  // or "ditolak"
}
```

Response (200):
```json
{
  "success": true,
  "message": "Pengajuan berhasil diterima",
  "data": {
    "application": { /* updated application */ }
  }
}
```

---

## 6. Smart Match Endpoints

### 6.1 Get Recommendations
**GET** `/smart-match/recommendations`

Headers: `Authorization: Bearer {token}`

Query params:
- `limit` (optional): Number of results (default: 10)

Response (200):
```json
{
  "success": true,
  "data": {
    "recommendations": [
      {
        "post": { /* post data */ },
        "match_score": 85.5
      }
    ],
    "count": 5
  }
}
```

### 6.2 Get Candidate Recommendations
**GET** `/smart-match/candidates/{postId}`

Headers: `Authorization: Bearer {token}`

Response (200):
```json
{
  "success": true,
  "data": {
    "recommendations": [
      {
        "user": { /* user data */ },
        "match_score": 90.0
      }
    ],
    "count": 8
  }
}
```

---

## 7. Admin Endpoints

**All admin endpoints require admin role**

### 7.1 User Management

**GET** `/admin/users` - List users  
**GET** `/admin/users/{id}` - User detail  
**PUT** `/admin/users/{id}` - Update user  
**POST** `/admin/users/{id}/activate` - Activate account  
**POST** `/admin/users/{id}/deactivate` - Deactivate account  
**DELETE** `/admin/users/{id}` - Delete user

### 7.2 Post Management

**GET** `/admin/posts` - List posts  
**GET** `/admin/posts/{id}` - Post detail  
**PUT** `/admin/posts/{id}` - Update post  
**POST** `/admin/posts/{id}/hide` - Hide post  
**POST** `/admin/posts/{id}/unhide` - Unhide post  
**DELETE** `/admin/posts/{id}` - Delete post

---

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 401 | Unauthorized (invalid credentials) |
| 403 | Forbidden (no permission) |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## Testing with Postman

1. Import collection dari: `docs/postman_collection.json`
2. Set environment variable `BASE_URL` = `http://localhost:8000/api`
3. Login untuk mendapatkan token
4. Set token di Authorization header

---

**Last Updated**: Oktober 2026
