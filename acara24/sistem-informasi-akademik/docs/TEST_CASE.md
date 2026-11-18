# Laporan Black Box Testing REST API Sistem Informasi Akademik

## 1. Pendahuluan

Dokumen ini memuat 20 test case black box untuk memverifikasi endpoint `AuthController` dan `MahasiswaController` pada Sistem Informasi Akademik, tanpa melihat kode sumber internal.

**Tujuan.** Memastikan setiap endpoint mengembalikan status HTTP dan isi response sesuai spesifikasi untuk input valid, input tidak valid, ID yang tidak ada, dan akses tanpa token.

**Metode.** Black box testing dengan pembagian kelas ekuivalen (equivalence partitioning): setiap endpoint diuji dengan satu atau lebih partisi valid dan partisi tidak valid.

**Alat.** Postman (manual), Apache, dan database lokal.

**Status saat ini.** Seluruh test case belum dijalankan; kolom hasil aktual dan status diisi setelah eksekusi di Postman.

## 2. Ruang Lingkup

Pengujian mencakup tujuh endpoint yang terdaftar pada `Router`; tiga di antaranya (POST, PUT, DELETE pada `/mahasiswa`) memerlukan token, sedangkan register, login, dan kedua GET tidak.

| Method | Endpoint | Controller | Autentikasi |
|---|---|---|---|
| POST | `/register` | `AuthController::register` | Tidak |
| POST | `/login` | `AuthController::login` | Tidak |
| GET | `/mahasiswa` | `MahasiswaController::index` | Tidak |
| GET | `/mahasiswa?id={id}` | `MahasiswaController::index` | Tidak |
| POST | `/mahasiswa` | `MahasiswaController::store` | Ya |
| PUT | `/mahasiswa?id={id}` | `MahasiswaController::update` | Ya |
| DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy` | Ya |

## 3. Test Case

Dua puluh test case dikelompokkan per endpoint. "Belum dijalankan" berarti request masih perlu dieksekusi di Postman; status tidak boleh diubah menjadi Pass sebelum status HTTP dan response aktual dicocokkan.

| ID | Skenario / partisi | Request dan data uji | Hasil yang diharapkan | Hasil aktual | Status |
|---|---|---|---|---|---|
| TC01 | Registrasi dengan data valid | `POST {{base_url}}/register`; username, email valid, password diisi | 201 Created; `success: true` | Belum dijalankan | Belum diuji |
| TC02 | Registrasi dengan field wajib kosong | `POST /register`; hilangkan `email` | 400 Bad Request | Belum dijalankan | Belum diuji |
| TC03 | Login dengan kredensial benar | `POST {{base_url}}/login`; akun yang telah terdaftar | 200 OK; response berisi `data.token` | Belum dijalankan | Belum diuji |
| TC04 | Login dengan password salah | `POST /login`; username terdaftar, password salah | 401 Unauthorized | Belum dijalankan | Belum diuji |
| TC05 | Ambil seluruh mahasiswa | `GET {{base_url}}/mahasiswa` | 200 OK; `data` berupa daftar | Belum dijalankan | Belum diuji |
| TC06 | Ambil mahasiswa yang ada | `GET {{base_url}}/mahasiswa?id={{mahasiswa_id}}` | 200 OK; `data` berisi satu mahasiswa | Belum dijalankan | Belum diuji |
| TC07 | Ambil mahasiswa dengan ID yang tidak ada | `GET /mahasiswa?id=999999` (pastikan ID belum digunakan) | 404 Not Found | Belum dijalankan | Belum diuji |
| TC08 | Tambah mahasiswa dengan NIM valid dan lengkap | `POST /mahasiswa`; `{"nim":"220100123","nama":"Budi Uji","prodi":"Informatika"}`; sertakan token | 201 Created; data berhasil ditambahkan | Belum dijalankan | Belum diuji |
| TC09 | Tambah mahasiswa dengan NIM kosong | `POST /mahasiswa`; `{"nim":"","nama":"Budi Uji","prodi":"Informatika"}`; sertakan token | 422 Unprocessable Entity; validasi NIM gagal | Belum dijalankan | Belum diuji |
| TC10 | Tambah mahasiswa dengan NIM duplikat | `POST /mahasiswa`; gunakan NIM milik data lain; sertakan token | 409 Conflict; NIM sudah terdaftar | Belum dijalankan | Belum diuji |
| TC11 | Tambah mahasiswa tanpa token | `POST /mahasiswa`; body valid, tanpa header Authorization | 401 Unauthorized | Belum dijalankan | Belum diuji |
| TC12 | Update seluruh field dengan data valid | `PUT /mahasiswa?id={{mahasiswa_id}}`; body lengkap dan NIM unik; sertakan token | 200 OK; data berhasil diperbarui | Belum dijalankan | Belum diuji |
| TC13 | Update dengan field wajib tidak lengkap | `PUT /mahasiswa?id={{mahasiswa_id}}`; hilangkan `prodi`; sertakan token | 400 Bad Request | Belum dijalankan | Belum diuji |
| TC14 | Update dengan NIM yang panjangnya tidak valid | `PUT /mahasiswa?id={{mahasiswa_id}}`; NIM `123`; field lain valid; sertakan token | 422 Unprocessable Entity | Belum dijalankan | Belum diuji |
| TC15 | Update dengan NIM milik mahasiswa lain | `PUT /mahasiswa?id={{mahasiswa_id}}`; NIM milik ID berbeda; sertakan token | 409 Conflict; NIM sudah digunakan mahasiswa lain | Belum dijalankan | Belum diuji |
| TC16 | Update ID yang tidak ditemukan | `PUT /mahasiswa?id=999999`; body valid; sertakan token | 404 Not Found | Belum dijalankan | Belum diuji |
| TC17 | Update tanpa token | `PUT /mahasiswa?id={{mahasiswa_id}}`; body valid, tanpa Authorization | 401 Unauthorized | Belum dijalankan | Belum diuji |
| TC18 | Hapus mahasiswa yang ada | `DELETE /mahasiswa?id={{mahasiswa_id}}`; sertakan token; gunakan data uji yang boleh dihapus | 200 OK; data berhasil dihapus | Belum dijalankan | Belum diuji |
| TC19 | Hapus ID yang tidak ditemukan | `DELETE /mahasiswa?id=999999`; sertakan token | 404 Not Found | Belum dijalankan | Belum diuji |
| TC20 | Hapus tanpa token | `DELETE /mahasiswa?id={{mahasiswa_id}}`; tanpa Authorization | 401 Unauthorized | Belum dijalankan | Belum diuji |

## 4. Body Postman dan Data Uji

Semua body menggunakan **Body → raw → JSON**. Nama field `username`, `email`, `password`, `nim`, `nama`, dan `prodi` mengikuti test case di atas; sesuaikan bila controller memakai nama lain.

**Data awal di database (`db_workshop.sql`):**

| id | nim | nama | Keterangan |
|---|---|---|---|
| 2 | `E41260001` | Andi Saputra | Jangan diubah/dihapus; dipakai TC06 dan TC10 |
| 3 | `E41260002` | Budi Santoso Update | Jangan diubah/dihapus; dipakai TC15 |

**Urutan eksekusi:** TC01 → TC03 (menyimpan `token`) → TC08 (menyimpan `mahasiswa_id`) → TC09–TC17 → TC18. TC18 menghapus data uji dari TC08, bukan id 2 atau 3. Setelah TC12, NIM data uji berubah menjadi `220100125`; ulangi TC08 dengan NIM lain bila ingin mengulang.

| ID | Method dan URL | Auth | Body (raw JSON) |
|---|---|---|---|
| TC01 | `POST {{base_url}}/register` | Tidak | `{"username":"uji_user","email":"uji_user@example.com","password":"Password123"}` |
| TC02 | `POST {{base_url}}/register` | Tidak | `{"username":"uji_user2","password":"Password123"}` |
| TC03 | `POST {{base_url}}/login` | Tidak | `{"username":"uji_user","password":"Password123"}` |
| TC04 | `POST {{base_url}}/login` | Tidak | `{"username":"uji_user","password":"PasswordSalah"}` |
| TC05 | `GET {{base_url}}/mahasiswa` | Tidak | tanpa body |
| TC06 | `GET {{base_url}}/mahasiswa?id=2` | Tidak | tanpa body |
| TC07 | `GET {{base_url}}/mahasiswa?id=999999` | Tidak | tanpa body |
| TC08 | `POST {{base_url}}/mahasiswa` | Bearer | `{"nim":"220100123","nama":"Budi Uji","prodi":"Informatika"}` |
| TC09 | `POST {{base_url}}/mahasiswa` | Bearer | `{"nim":"","nama":"Budi Uji","prodi":"Informatika"}` |
| TC10 | `POST {{base_url}}/mahasiswa` | Bearer | `{"nim":"E41260001","nama":"Duplikat Uji","prodi":"Informatika"}` |
| TC11 | `POST {{base_url}}/mahasiswa` | Tidak | `{"nim":"220100124","nama":"Tanpa Token","prodi":"Informatika"}` |
| TC12 | `PUT {{base_url}}/mahasiswa?id={{mahasiswa_id}}` | Bearer | `{"nim":"220100125","nama":"Budi Uji Update","prodi":"Teknik Informatika"}` |
| TC13 | `PUT {{base_url}}/mahasiswa?id={{mahasiswa_id}}` | Bearer | `{"nim":"220100125","nama":"Budi Uji Update"}` |
| TC14 | `PUT {{base_url}}/mahasiswa?id={{mahasiswa_id}}` | Bearer | `{"nim":"123","nama":"Budi Uji Update","prodi":"Teknik Informatika"}` |
| TC15 | `PUT {{base_url}}/mahasiswa?id={{mahasiswa_id}}` | Bearer | `{"nim":"E41260002","nama":"Budi Uji Update","prodi":"Teknik Informatika"}` |
| TC16 | `PUT {{base_url}}/mahasiswa?id=999999` | Bearer | `{"nim":"220100126","nama":"ID Tidak Ada","prodi":"Teknik Informatika"}` |
| TC17 | `PUT {{base_url}}/mahasiswa?id={{mahasiswa_id}}` | Tidak | `{"nim":"220100125","nama":"Tanpa Token","prodi":"Teknik Informatika"}` |
| TC18 | `DELETE {{base_url}}/mahasiswa?id={{mahasiswa_id}}` | Bearer | tanpa body |
| TC19 | `DELETE {{base_url}}/mahasiswa?id=999999` | Bearer | tanpa body |
| TC20 | `DELETE {{base_url}}/mahasiswa?id={{mahasiswa_id}}` | Tidak | tanpa body |