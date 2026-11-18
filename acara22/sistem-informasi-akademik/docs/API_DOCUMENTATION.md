
# API Documentation
## Sistem Informasi Akademik

### 1. Informasi Umum

Dokumentasi ini menjelaskan endpoint REST API pada aplikasi Sistem Informasi Akademik.

API digunakan untuk mengelola data mahasiswa, data buku, serta autentikasi pengguna melalui mekanisme JSON Web Token (JWT).

**Base URL:**

`http://localhost/workshop_si_web_server/acara21/htdocs/sistem-informasi-akademik/public`

**Format Data:** JSON

**Content-Type:** `application/json`

### 2. Authentication

API menggunakan JSON Web Token (JWT) untuk melindungi beberapa endpoint.

Token diperoleh melalui endpoint `POST /login`.

Untuk endpoint yang membutuhkan autentikasi, sertakan header:

`Authorization: Bearer <token>`

Masa berlaku token adalah 1 jam.

### 3. Daftar Endpoint


| No. | Method | Endpoint | Controller | Authentication |
|---|---|---|---|---|
| 1 | POST | /register | AuthController | Tidak |
| 2 | POST | /login | AuthController | Tidak |
| 3 | GET | /mahasiswa | MahasiswaController | Tidak |
| 4 | GET | /mahasiswa?id={id} | MahasiswaController | Tidak |
| 5 | POST | /mahasiswa | MahasiswaController | Ya |
| 6 | PUT | /mahasiswa?id={id} | MahasiswaController | Ya |
| 7 | DELETE | /mahasiswa?id={id} | MahasiswaController | Ya |
| 8 | GET | /buku | BukuController | Tidak |
| 9 | GET | /buku?id={id} | BukuController | Tidak |
| 10 | POST | /buku | BukuController | Tidak |
| 11 | PUT | /buku?id={id} | BukuController | Tidak |
| 12 | DELETE | /buku?id={id} | BukuController | Tidak |


**Catatan:**
- Endpoint mahasiswa menggunakan format response standar melalui `Response::json()`.
- Endpoint buku menggunakan format response JSON yang ditulis langsung di `BukuController`.
- Parameter `id` digunakan untuk mengambil, memperbarui, atau menghapus satu data.


---

### 4. Authentication Endpoints

#### 4.1 Register

Mendaftarkan pengguna baru ke dalam sistem.

- **Method:** POST
- **Endpoint:** `/register`
- **Controller:** `AuthController::register`
- **Authentication:** Tidak diperlukan.
- **Content-Type:** `application/json`

**Request Body:**

| Parameter | Tipe Data | Wajib | Keterangan |
|---|---|---|---|
| username | String | Ya | Username pengguna |
| email | String | Ya | Email dengan format valid |
| password | String | Ya | Password pengguna |

**Contoh Request:**

```json
{
  "username": "admin",
  "email": "admin@example.com",
  "password": "rahasia123"
}
```

**Success Response — HTTP 201 Created**

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "id": "1"
  },
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 400 Bad Request**

Terjadi jika username, email, atau password tidak diisi.

```json
{
  "success": false,
  "message": "Username, email, dan password wajib diisi",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 422 Unprocessable Entity**

Terjadi jika format email tidak valid.

```json
{
  "success": false,
  "message": "Format email tidak valid",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

#### 4.2 Login

Melakukan autentikasi pengguna dan menghasilkan JWT.

- **Method:** POST
- **Endpoint:** `/login`
- **Controller:** `AuthController::login`
- **Authentication:** Tidak diperlukan.
- **Content-Type:** `application/json`

**Request Body:**

| Parameter | Tipe Data | Wajib | Keterangan |
|---|---|---|---|
| username | String | Ya | Username pengguna |
| password | String | Ya | Password pengguna |

**Contoh Request:**

```json
{
  "username": "admin",
  "password": "rahasia123"
}
```

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "TOKEN_JWT",
    "role": "admin"
  },
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 400 Bad Request**

Terjadi jika username atau password tidak diisi.

```json
{
  "success": false,
  "message": "Username dan password wajib diisi",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 401 Unauthorized**

Terjadi jika username atau password salah.

```json
{
  "success": false,
  "message": "Username atau password salah",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

#### 4.3 Token Authentication

API menggunakan JSON Web Token (JWT) dengan algoritma HS256.

**Token Information:**

| Keterangan | Nilai |
|---|---|
| Token Type | Bearer |
| Algorithm | HS256 |
| Expiration | 3600 seconds (1 hour) |
| Token Payload | id, role, iat, exp |

**Required Header:**

```http
Authorization: Bearer <token>
```

**Error Response — HTTP 401 Unauthorized**

Terjadi jika token tidak disertakan dalam request.

```json
{
  "success": false,
  "message": "Token tidak ditemukan",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

Terjadi jika token tidak valid atau sudah kedaluwarsa.

```json
{
  "success": false,
  "message": "Token tidak valid atau sudah kedaluwarsa",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

---

### 5. Mahasiswa Endpoints

#### 5.1 Get All Mahasiswa

Mengambil seluruh data mahasiswa.

- **Method:** GET
- **Endpoint:** `/mahasiswa`
- **Controller:** `MahasiswaController::index`
- **Authentication:** Tidak diperlukan.
- **Content-Type:** `application/json`

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    {
      "id": 1,
      "nim": "232410001",
      "nama": "Budi Santoso",
      "prodi": "Teknik Informatika"
    }
  ],
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

#### 5.2 Get Mahasiswa By ID

Mengambil satu data mahasiswa berdasarkan ID.

- **Method:** GET
- **Endpoint:** `/mahasiswa?id={id}`
- **Controller:** `MahasiswaController::index`
- **Authentication:** Tidak diperlukan.

**Query Parameter:**

| Parameter | Tipe Data | Wajib | Keterangan |
|---|---|---|---|
| id | Integer | Ya | ID mahasiswa |

**Contoh URL:**

`/mahasiswa?id=1`

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {
    "id": 1,
    "nim": "232410001",
    "nama": "Budi Santoso",
    "prodi": "Teknik Informatika"
  },
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 404 Not Found**

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

#### 5.3 Create Mahasiswa

Menambahkan data mahasiswa baru.

- **Method:** POST
- **Endpoint:** `/mahasiswa`
- **Controller:** `MahasiswaController::store`
- **Authentication:** Wajib menggunakan Bearer Token.
- **Content-Type:** `application/json`

**Request Headers:**

| Header | Value |
|---|---|
| Content-Type | application/json |
| Authorization | Bearer {token} |

**Request Body:**

| Parameter | Tipe Data | Wajib | Keterangan |
|---|---|---|---|
| nim | String | Ya | NIM, harus 9 karakter |
| nama | String | Ya | Nama mahasiswa |
| prodi | String | Ya | Program studi |

**Contoh Request:**

```json
{
  "nim": "232410001",
  "nama": "Budi Santoso",
  "prodi": "Teknik Informatika"
}
```

**Success Response — HTTP 201 Created**

```json
{
  "success": true,
  "message": "Data berhasil ditambahkan",
  "data": {
    "id": "1"
  },
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 422 Unprocessable Entity**

Terjadi jika NIM, nama, atau program studi tidak diisi, atau NIM tidak terdiri dari 9 karakter.


**Contoh 1 — HTTP 422 Unprocessable Entity (Validasi field)**

```json
{
  "success": false,
  "message": "Validasi gagal",
  "data": null,
  "errors": {
    "nim": "NIM wajib diisi",
    "nama": "Nama wajib diisi",
    "prodi": "Program studi wajib diisi"
  },
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Contoh 2 — HTTP 422 Unprocessable Entity (NIM tidak valid)**

```json
{
  "success": false,
  "message": "NIM harus terdiri dari 9 karakter",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 400 Bad Request**

Terjadi jika format JSON yang dikirim tidak valid.

```json
{
  "success": false,
  "message": "Format JSON tidak valid",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 409 Conflict**

Terjadi jika data mahasiswa bertentangan dengan aturan pada database atau model.

```json
{
  "success": false,
  "message": "Pesan kesalahan dari sistem",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 500 Internal Server Error**

Terjadi jika terdapat kesalahan saat proses penyimpanan data.

```json
{
  "success": false,
  "message": "Pesan kesalahan dari sistem",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

#### 5.4 Update Mahasiswa

Memperbarui data mahasiswa berdasarkan ID.

- **Method:** PUT
- **Endpoint:** `/mahasiswa?id={id}`
- **Controller:** `MahasiswaController::update`
- **Authentication:** Wajib menggunakan Bearer Token.
- **Content-Type:** `application/json`

**Query Parameter:**

| Parameter | Tipe Data | Wajib | Keterangan |
|---|---|---|---|
| id | Integer | Ya | ID mahasiswa |

**Request Body:**

```json
{
  "nim": "232410002",
  "nama": "Andi Saputra",
  "prodi": "Teknik Informatika"
}
```

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "message": "Data berhasil diperbarui",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 400 Bad Request**

Terjadi jika field nim, nama, atau prodi tidak diisi.

```json
{
  "success": false,
  "message": "Field nim, nama, dan prodi wajib diisi",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 404 Not Found**

Terjadi jika ID mahasiswa tidak ditemukan.

```json
{
  "success": false,
  "message": "Data mahasiswa tidak ditemukan",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 422 Unprocessable Entity**

Terjadi jika NIM tidak terdiri dari 9 karakter.

```json
{
  "success": false,
  "message": "NIM harus terdiri dari 9 karakter",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

**Error Response — HTTP 409 Conflict**

Terjadi jika data mahasiswa bertentangan dengan aturan pada database atau model.

**Error Response — HTTP 500 Internal Server Error**

Terjadi jika terdapat kesalahan saat proses pembaruan data.

#### 5.5 Delete Mahasiswa

Menghapus data mahasiswa berdasarkan ID.

- **Method:** DELETE
- **Endpoint:** `/mahasiswa?id={id}`
- **Controller:** `MahasiswaController::destroy`
- **Authentication:** Wajib menggunakan Bearer Token.

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "message": "Data berhasil dihapus",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```


**Error Response — HTTP 404 Not Found**

Terjadi jika ID mahasiswa tidak ditemukan.

```json id="x4rm2q"
{
  "success": false,
  "message": "Data mahasiswa tidak ditemukan",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-11T10:00:00+07:00"
}
```

---

### 6. Buku Endpoints

#### 6.1 Get All Buku

Mengambil seluruh data buku.

- **Method:** GET
- **Endpoint:** `/buku`
- **Controller:** `BukuController::index`
- **Authentication:** Tidak diperlukan.

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "judul": "Pemrograman Web",
      "penulis": "Andi",
      "penerbit": "Informatika",
      "tahun_terbit": 2024
    }
  ]
}
```

#### 6.2 Get Buku By ID

Mengambil satu data buku berdasarkan ID.

- **Method:** GET
- **Endpoint:** `/buku?id={id}`
- **Controller:** `BukuController::index`
- **Authentication:** Tidak diperlukan.

**Contoh URL:**

`/buku?id=1`

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "judul": "Pemrograman Web",
    "penulis": "Andi",
    "penerbit": "Informatika",
    "tahun_terbit": 2024
  }
}
```

**Error Response — HTTP 404 Not Found**

```json
{
  "success": false,
  "message": "Data buku tidak ditemukan"
}
```

#### 6.3 Create Buku

Menambahkan data buku baru.

- **Method:** POST
- **Endpoint:** `/buku`
- **Controller:** `BukuController::store`
- **Authentication:** Tidak diperlukan.
- **Content-Type:** `application/json`

**Request Body:**

| Parameter | Tipe Data | Wajib | Keterangan |
|---|---|---|---|
| judul | String | Ya | Judul buku |
| penulis | String | Ya | Nama penulis |
| penerbit | String | Ya | Nama penerbit |
| tahun_terbit | Integer | Ya | Tahun penerbitan |

**Contoh Request:**

```json
{
  "judul": "Pemrograman Web",
  "penulis": "Andi",
  "penerbit": "Informatika",
  "tahun_terbit": 2024
}
```

**Success Response — HTTP 201 Created**

```json
{
  "success": true,
  "message": "Data buku berhasil ditambahkan",
  "data": {
    "id": 1
  }
}
```

**Error Response — HTTP 400 Bad Request**

Terjadi jika format JSON tidak valid atau ada field yang belum diisi.

```json
{
  "success": false,
  "message": "Semua field wajib diisi"
}
```

#### 6.4 Update Buku

Memperbarui data buku berdasarkan ID.

- **Method:** PUT
- **Endpoint:** `/buku?id={id}`
- **Controller:** `BukuController::update`
- **Authentication:** Tidak diperlukan.
- **Content-Type:** `application/json`

**Request Body:**

```json
{
  "judul": "Dasar Pemrograman PHP",
  "penulis": "Budi",
  "penerbit": "Informatika",
  "tahun_terbit": 2025
}
```

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "message": "Data buku berhasil diperbarui"
}
```

**Error Response — HTTP 400 Bad Request**

Terjadi jika ID tidak diberikan atau ada field yang belum diisi.

```json
{
  "success": false,
  "message": "ID dan semua field wajib diisi"
}
```

**Error Response — HTTP 404 Not Found**

Terjadi jika ID buku tidak ditemukan.

```json
{
  "success": false,
  "message": "Data buku tidak ditemukan"
}
```

#### 6.5 Delete Buku

Menghapus data buku berdasarkan ID.

- **Method:** DELETE
- **Endpoint:** `/buku?id={id}`
- **Controller:** `BukuController::destroy`
- **Authentication:** Tidak diperlukan.

**Success Response — HTTP 200 OK**

```json
{
  "success": true,
  "message": "Data buku berhasil dihapus"
}
```

**Error Response — HTTP 404 Not Found**

```json
{
  "success": false,
  "message": "Data buku tidak ditemukan"
}
```