// URL dasar API Acara 25
const BASE_URL =
    'http://localhost/workshop_si_web_server/acara25/sistem-informasi-akademik/public';

// Mengambil token dan role dari localStorage
const token = localStorage.getItem('token');
const role = localStorage.getItem('role');
const username = localStorage.getItem('username');

// Memeriksa apakah pengguna sudah login
if (!token) {
    window.location.href = 'login.html';
}

// Menampilkan informasi pengguna
document.getElementById('usernameDisplay').textContent =
    username || 'Pengguna';

document.getElementById('roleDisplay').textContent =
    role || 'Tidak diketahui';

// Elemen halaman
const mahasiswaTable = document.getElementById('mahasiswaTable');
const totalMahasiswa = document.getElementById('totalMahasiswa');
const alertMessage = document.getElementById('alertMessage');

const addButton = document.getElementById('addButton');
const mahasiswaForm = document.getElementById('mahasiswaForm');
const mahasiswaModal = document.getElementById('mahasiswaModal');

const modalTitle = document.getElementById('modalTitle');
const mahasiswaId = document.getElementById('mahasiswaId');

const nimInput = document.getElementById('nim');
const namaInput = document.getElementById('nama');
const prodiInput = document.getElementById('prodi');

const saveButton = document.getElementById('saveButton');

// Bootstrap modal
const modal = new bootstrap.Modal(mahasiswaModal);

// Menyimpan data mahasiswa sementara
let mahasiswaData = [];

// Memeriksa role pengguna
const isAdmin = role === 'admin';

// Menyembunyikan tombol CRUD jika bukan admin
if (!isAdmin) {
    addButton.style.display = 'none';
    document.getElementById('actionHeader').style.display = 'none';
}

// Menampilkan pesan
function showMessage(message, type = 'success') {
    alertMessage.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    `;
}

// Menghindari HTML injection pada data
function escapeHTML(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

// Mengambil data mahasiswa dari API
async function loadMahasiswa() {
    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            if (response.status === 401) {
                localStorage.clear();
                window.location.href = 'login.html';
                return;
            }

            showMessage(result.message || 'Gagal mengambil data mahasiswa', 'danger');
            return;
        }

        // Mengambil data dari respons API
        mahasiswaData = result.data;

        // Memastikan data berbentuk array
        if (!Array.isArray(mahasiswaData)) {
            mahasiswaData = [];
        }

        renderMahasiswa();

    } catch (error) {
        console.error('Error:', error);

        mahasiswaTable.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-danger">
                    Gagal menghubungkan ke server
                </td>
            </tr>
        `;
    }
}

// Menampilkan data mahasiswa ke tabel
function renderMahasiswa() {
    totalMahasiswa.textContent = mahasiswaData.length;

    if (mahasiswaData.length === 0) {
        mahasiswaTable.innerHTML = `
            <tr>
                <td colspan="5" class="text-center">
                    Belum ada data mahasiswa
                </td>
            </tr>
        `;
        return;
    }

    mahasiswaTable.innerHTML = mahasiswaData.map((mahasiswa, index) => {

        let actionButtons = '';

        if (isAdmin) {
            actionButtons = `
                <td class="action-buttons">
                    <button
                        class="btn btn-warning btn-sm editButton"
                        data-id="${escapeHTML(mahasiswa.id)}"
                    >
                        Edit
                    </button>

                    <button
                        class="btn btn-danger btn-sm deleteButton"
                        data-id="${escapeHTML(mahasiswa.id)}"
                    >
                        Hapus
                    </button>
                </td>
            `;
        }

        return `
            <tr>
                <td>${index + 1}</td>
                <td>${escapeHTML(mahasiswa.nim)}</td>
                <td>${escapeHTML(mahasiswa.nama)}</td>
                <td>${escapeHTML(mahasiswa.prodi)}</td>
                ${actionButtons}
            </tr>
        `;
    }).join('');
}

// Membuka modal tambah mahasiswa
addButton.addEventListener('click', function () {
    mahasiswaForm.reset();

    mahasiswaId.value = '';
    modalTitle.textContent = 'Tambah Mahasiswa';
    saveButton.textContent = 'Simpan';
});

// Menangani tambah dan edit mahasiswa
mahasiswaForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    if (!isAdmin) {
        showMessage('Akses ditolak. Hanya admin yang diizinkan.', 'danger');
        return;
    }

    const id = mahasiswaId.value;

    const data = {
        nim: nimInput.value.trim(),
        nama: namaInput.value.trim(),
        prodi: prodiInput.value.trim()
    };

    const isEdit = id !== '';

    const url = isEdit
        ? `${BASE_URL}/mahasiswa/${id}`
        : `${BASE_URL}/mahasiswa`;

    const method = isEdit ? 'PUT' : 'POST';

    saveButton.disabled = true;
    saveButton.textContent = 'Menyimpan...';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            showMessage(result.message || 'Gagal menyimpan data', 'danger');
            return;
        }

        modal.hide();
        mahasiswaForm.reset();

        showMessage(
            isEdit
                ? 'Data mahasiswa berhasil diperbarui'
                : 'Data mahasiswa berhasil ditambahkan'
        );

        // Memuat ulang data mahasiswa
        await loadMahasiswa();

    } catch (error) {
        console.error('Error:', error);
        showMessage('Terjadi kesalahan saat menyimpan data', 'danger');

    } finally {
        saveButton.disabled = false;
        saveButton.textContent = 'Simpan';
    }
});

// Menangani tombol Edit dan Hapus
mahasiswaTable.addEventListener('click', async function (event) {

    // Tombol Edit
    if (event.target.classList.contains('editButton')) {

        if (!isAdmin) {
            return;
        }

        const id = event.target.dataset.id;

        const mahasiswa = mahasiswaData.find(
            item => String(item.id) === String(id)
        );

        if (!mahasiswa) {
            showMessage('Data mahasiswa tidak ditemukan', 'danger');
            return;
        }

        mahasiswaId.value = mahasiswa.id;
        nimInput.value = mahasiswa.nim;
        namaInput.value = mahasiswa.nama;
        prodiInput.value = mahasiswa.prodi;

        modalTitle.textContent = 'Edit Mahasiswa';
        saveButton.textContent = 'Update';

        modal.show();
    }

    // Tombol Hapus
    if (event.target.classList.contains('deleteButton')) {

        if (!isAdmin) {
            return;
        }

        const id = event.target.dataset.id;

        const konfirmasi = confirm(
            'Apakah kamu yakin ingin menghapus data mahasiswa ini?'
        );

        if (!konfirmasi) {
            return;
        }

        try {
            const response = await fetch(`${BASE_URL}/mahasiswa/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                showMessage(result.message || 'Gagal menghapus data', 'danger');
                return;
            }

            showMessage('Data mahasiswa berhasil dihapus');

            await loadMahasiswa();

        } catch (error) {
            console.error('Error:', error);
            showMessage('Terjadi kesalahan saat menghapus data', 'danger');
        }
    }

});

// Logout
document.getElementById('logoutButton').addEventListener('click', function () {

    const konfirmasi = confirm('Apakah kamu yakin ingin logout?');

    if (konfirmasi) {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        localStorage.removeItem('username');

        window.location.href = 'login.html';
    }

});

// Memuat data mahasiswa ketika dashboard dibuka
loadMahasiswa();