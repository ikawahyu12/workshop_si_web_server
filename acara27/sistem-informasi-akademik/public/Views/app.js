const BASE_URL = 'http://localhost/sistem-informasi-akademik/public';

// Cek proteksi halaman (kecuali halaman publik jika ada)
function cekAutentikasi() {
    if (!localStorage.getItem('token')) {
        window.location.href = 'login.html';
    }
}

// TUGAS MANDIRI 1: Notifikasi Visual (Bootstrap Alert dinamis)
function tampilkanNotifikasi(pesan, tipe = 'success') {
    const wadah = document.getElementById('alert-container');
    if (!wadah) return;

    const ikon = tipe === 'success' ? '✅' : '❌';
    wadah.innerHTML = `
        <div class="alert alert-${tipe} alert-dismissible fade show shadow-sm" role="alert">
            <strong>${ikon}</strong> ${pesan}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    // Hilangkan otomatis setelah 4 detik
    setTimeout(() => {
        const alertEl = wadah.querySelector('.alert');
        if (alertEl) {
            alertEl.classList.remove('show');
            setTimeout(() => wadah.innerHTML = '', 150);
        }
    }, 4000);
}

// TUGAS MANDIRI 2: Validasi Sisi View sebelum dikirim ke Controller
function validasiFormMahasiswa(nim, nama, prodi) {
    if (!nim.trim()) {
        tampilkanNotifikasi('NIM wajib diisi!', 'danger');
        return false;
    }
    if (!nama.trim()) {
        tampilkanNotifikasi('Nama mahasiswa wajib diisi!', 'danger');
        return false;
    }
    if (!prodi.trim()) {
        tampilkanNotifikasi('Program Studi wajib diisi!', 'danger');
        return false;
    }
    return true;
}

// --- READ: Memuat Data Mahasiswa ke Tabel Dashboard ---
async function muatDataMahasiswa() {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');
    if (!tbody) return;

    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Memuat data...</td></tr>';

    try {
        const res = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await res.json();

        if (!hasil.success || hasil.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Belum ada data mahasiswa.</td></tr>';
            return;
        }

        const role = localStorage.getItem('role');
        tbody.innerHTML = '';

        hasil.data.forEach((mhs) => {
            const fotoUrl = mhs.foto ? `../uploads/${mhs.foto}` : 'https://via.placeholder.com/50?text=No+Img';
            
            // Sembunyikan tombol aksi jika bukan admin (Acara 25)
            const tombolAksi = role === 'admin' ? `
                <a href="form_edit.html?id=${mhs.id}" class="btn btn-warning btn-sm">Edit</a>
                <button onclick="hapusData(${mhs.id})" class="btn btn-danger btn-sm">Hapus</button>
            ` : '<span class="badge bg-secondary">Read Only</span>';

            tbody.innerHTML += `
                <tr>
                    <td><img src="${fotoUrl}" alt="Foto" width="50" height="50" class="rounded object-fit-cover"></td>
                    <td>${mhs.nim}</td>
                    <td>${mhs.nama}</td>
                    <td>${mhs.prodi}</td>
                    <td>${tombolAksi}</td>
                </tr>
            `;
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data dari server.</td></tr>';
        tampilkanNotifikasi('Terjadi kesalahan koneksi ke server.', 'danger');
    }
}

// --- CREATE: Tambah Data (form_tambah.html) ---
async function tambahData(e) {
    e.preventDefault();

    const nim = document.getElementById('nim').value;
    const nama = document.getElementById('nama').value;
    const prodi = document.getElementById('prodi').value;
    const inputFoto = document.getElementById('foto');

    // Jalankan validasi sisi View
    if (!validasiFormMahasiswa(nim, nama, prodi)) return;

    const formData = new FormData();
    formData.append('nim', nim);
    formData.append('nama', nama);
    formData.append('prodi', prodi);
    if (inputFoto.files.length > 0) {
        formData.append('foto', inputFoto.files[0]);
    }

    try {
        const res = await fetch(`${BASE_URL}/mahasiswa`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: formData
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('flash_msg', JSON.stringify({ pesan: hasil.message, tipe: 'success' }));
            window.location.href = 'dashboard.html';
        } else {
            tampilkanNotifikasi(hasil.message || 'Gagal menambahkan data.', 'danger');
        }
    } catch (error) {
        tampilkanNotifikasi('Gagal terhubung ke server.', 'danger');
    }
}

// --- UPDATE (1): Muat Data Lama ke form_edit.html ---
async function muatDataUntukEdit(id) {
    try {
        const res = await fetch(`${BASE_URL}/mahasiswa?id=${id}`);
        const hasil = await res.json();

        if (hasil.success) {
            document.getElementById('nim').value = hasil.data.nim;
            document.getElementById('nama').value = hasil.data.nama;
            document.getElementById('prodi').value = hasil.data.prodi;
        } else {
            tampilkanNotifikasi('Data mahasiswa tidak ditemukan!', 'danger');
        }
    } catch (error) {
        tampilkanNotifikasi('Gagal mengambil data untuk diedit.', 'danger');
    }
}

// --- UPDATE (2): Simpan Perubahan (form_edit.html) ---
async function simpanPerubahan(e, id) {
    e.preventDefault();

    const nim = document.getElementById('nim').value;
    const nama = document.getElementById('nama').value;
    const prodi = document.getElementById('prodi').value;

    // Jalankan validasi sisi View
    if (!validasiFormMahasiswa(nim, nama, prodi)) return;

    const formData = new FormData();
    formData.append('nim', nim);
    formData.append('nama', nama);
    formData.append('prodi', prodi);

    try {
        const res = await fetch(`${BASE_URL}/mahasiswa?id=${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: formData
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('flash_msg', JSON.stringify({ pesan: hasil.message, tipe: 'success' }));
            window.location.href = 'dashboard.html';
        } else {
            tampilkanNotifikasi(hasil.message || 'Gagal memperbarui data.', 'danger');
        }
    } catch (error) {
        tampilkanNotifikasi('Gagal terhubung ke server.', 'danger');
    }
}

// --- DELETE: Hapus Data dari Tabel Dashboard ---
async function hapusData(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')) return;

    try {
        const res = await fetch(`${BASE_URL}/mahasiswa?id=${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        const hasil = await res.json();

        if (hasil.success) {
            tampilkanNotifikasi(hasil.message || 'Data berhasil dihapus!', 'success');
            muatDataMahasiswa(); // Reload tabel tanpa refresh penuh
        } else {
            tampilkanNotifikasi(hasil.message || 'Gagal menghapus data.', 'danger');
        }
    } catch (error) {
        tampilkanNotifikasi('Gagal terhubung ke server saat menghapus.', 'danger');
    }
}

// Menampilkan notifikasi lintas halaman (setelah redirect dari tambah/edit)
document.addEventListener('DOMContentLoaded', () => {
    const flash = localStorage.getItem('flash_msg');
    if (flash) {
        const { pesan, tipe } = JSON.parse(flash);
        tampilkanNotifikasi(pesan, tipe);
        localStorage.removeItem('flash_msg');
    }
});