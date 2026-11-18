/*
|--------------------------------------------------------------------------
| Dashboard Sistem Informasi Akademik - Acara 26 & 27
|--------------------------------------------------------------------------
| Fitur:
| - Login dan autentikasi token
| - Role admin dan user
| - Menampilkan data mahasiswa
| - Menambahkan mahasiswa dengan foto
| - Mengubah data mahasiswa
| - Menghapus data mahasiswa
| - Preview foto
| - Validasi foto
| - Notifikasi visual Create/Update/Delete (berhasil & gagal)
| - Validasi sisi View sebelum dikirim ke Controller
|--------------------------------------------------------------------------
*/

// Membungkus kode agar variabel tidak berada di scope global
(function () {

    // Mencegah dashboard diinisialisasi lebih dari satu kali
    if (window.__dashboardAcara26Initialized) {
        return;
    }

    window.__dashboardAcara26Initialized = true;

    // URL dasar API Acara 26
    const BASE_URL =
        'http://localhost/workshop_si_web_server/acara26/sistem-informasi-akademik/public';

    // URL folder uploads
    const UPLOAD_URL = `${BASE_URL}/uploads`;

    // Mengambil token dan role dari localStorage
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');
    const username = localStorage.getItem('username');

    // Memeriksa apakah pengguna sudah login
    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    // Menampilkan informasi pengguna
    document.getElementById('usernameDisplay').textContent =
        username || 'Pengguna';

    document.getElementById('roleDisplay').textContent =
        role || 'Tidak diketahui';

    // Elemen halaman
    const mahasiswaTable =
        document.getElementById('mahasiswaTable');

    const totalMahasiswa =
        document.getElementById('totalMahasiswa');

    const alertMessage =
        document.getElementById('alertMessage');

    const modalAlertMessage =
        document.getElementById('modalAlertMessage');

    const addButton =
        document.getElementById('addButton');

    const mahasiswaForm =
        document.getElementById('mahasiswaForm');

    const mahasiswaModal =
        document.getElementById('mahasiswaModal');

    const modalTitle =
        document.getElementById('modalTitle');

    const mahasiswaId =
        document.getElementById('mahasiswaId');

    const nimInput =
        document.getElementById('nim');

    const namaInput =
        document.getElementById('nama');

    const prodiInput =
        document.getElementById('prodi');

    const fotoInput =
        document.getElementById('foto');

    const fotoPreview =
        document.getElementById('fotoPreview');

    const fotoPreviewContainer =
        document.getElementById('fotoPreviewContainer');

    const saveButton =
        document.getElementById('saveButton');

    const logoutButton =
        document.getElementById('logoutButton');

    // Elemen pesan error validasi sisi View
    const nimError =
        document.getElementById('nimError');

    const namaError =
        document.getElementById('namaError');

    const prodiError =
        document.getElementById('prodiError');

    const fotoError =
        document.getElementById('fotoError');

    // Bootstrap modal
    const modal = new bootstrap.Modal(mahasiswaModal);

    // Menyimpan data mahasiswa sementara
    let mahasiswaData = [];

    // Menyimpan timer auto-dismiss notifikasi
    let alertTimerDashboard = null;
    let alertTimerModal = null;

    // Memeriksa role pengguna
    const isAdmin = role === 'admin';

    // Menyembunyikan tombol CRUD jika bukan admin
    if (!isAdmin) {
        addButton.style.display = 'none';

        document.getElementById('actionHeader').style.display =
            'none';
    }

    // =========================================================================
    // 1. NOTIFIKASI VISUAL (Berhasil & Gagal untuk Create, Update, dan Delete)
    // =========================================================================
    function showMessage(message, type = 'success', target = 'dashboard') {
        const container =
            target === 'modal' && modalAlertMessage
                ? modalAlertMessage
                : alertMessage;

        if (!container) {
            return;
        }

        const icon = type === 'success' ? '✅' : '❌';
        const label = type === 'success' ? 'Berhasil!' : 'Gagal!';

        container.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show shadow-sm" role="alert">
                <strong>${icon} ${label}</strong> ${escapeHTML(message)}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>
            </div>
        `;

        // Auto-hide setelah 4.5 detik
        if (target === 'modal') {
            if (alertTimerModal) clearTimeout(alertTimerModal);
            alertTimerModal = setTimeout(() => {
                const alertEl = container.querySelector('.alert');
                if (alertEl) {
                    bootstrap.Alert.getOrCreateInstance(alertEl).close();
                }
            }, 4500);
        } else {
            if (alertTimerDashboard) clearTimeout(alertTimerDashboard);
            alertTimerDashboard = setTimeout(() => {
                const alertEl = container.querySelector('.alert');
                if (alertEl) {
                    bootstrap.Alert.getOrCreateInstance(alertEl).close();
                }
            }, 4500);
        }
    }

    // =========================================================================
    // 2. VALIDASI SISI VIEW (Lapisan tambahan sebelum dikirim ke Controller)
    // =========================================================================
    function resetValidationState() {
        [nimInput, namaInput, prodiInput, fotoInput].forEach((input) => {
            if (input) {
                input.classList.remove('is-invalid', 'is-valid');
            }
        });

        if (modalAlertMessage) {
            modalAlertMessage.innerHTML = '';
        }
    }

    function setFieldInvalid(inputEl, errorEl, errorMessage) {
        if (inputEl) {
            inputEl.classList.remove('is-valid');
            inputEl.classList.add('is-invalid');
        }
        if (errorEl) {
            errorEl.textContent = errorMessage;
        }
    }

    function setFieldValid(inputEl) {
        if (inputEl) {
            inputEl.classList.remove('is-invalid');
            inputEl.classList.add('is-valid');
        }
    }

    function validateViewForm(nim, nama, prodi, fileFoto) {
        resetValidationState();

        let isValid = true;
        let firstInvalidEl = null;

        // Validasi NIM (Wajib diisi & tepat 9 karakter)
        if (!nim) {
            setFieldInvalid(nimInput, nimError, 'NIM wajib diisi.');
            isValid = false;
            firstInvalidEl = firstInvalidEl || nimInput;
        } else if (nim.length !== 9) {
            setFieldInvalid(
                nimInput,
                nimError,
                'NIM harus terdiri dari tepat 9 karakter.'
            );
            isValid = false;
            firstInvalidEl = firstInvalidEl || nimInput;
        } else {
            setFieldValid(nimInput);
        }

        // Validasi Nama (Wajib diisi)
        if (!nama) {
            setFieldInvalid(namaInput, namaError, 'Nama mahasiswa wajib diisi.');
            isValid = false;
            firstInvalidEl = firstInvalidEl || namaInput;
        } else {
            setFieldValid(namaInput);
        }

        // Validasi Program Studi (Wajib diisi)
        if (!prodi) {
            setFieldInvalid(prodiInput, prodiError, 'Program studi wajib diisi.');
            isValid = false;
            firstInvalidEl = firstInvalidEl || prodiInput;
        } else {
            setFieldValid(prodiInput);
        }

        // Validasi File Foto (Jika dipilih, wajib format JPG/PNG & maks 2 MB)
        if (fileFoto) {
            const formatValid = ['image/jpeg', 'image/png'];

            if (!formatValid.includes(fileFoto.type)) {
                setFieldInvalid(
                    fotoInput,
                    fotoError,
                    'Foto harus berformat JPG atau PNG.'
                );
                isValid = false;
                firstInvalidEl = firstInvalidEl || fotoInput;
            } else if (fileFoto.size > 2 * 1024 * 1024) {
                setFieldInvalid(
                    fotoInput,
                    fotoError,
                    'Ukuran foto maksimal 2 MB.'
                );
                isValid = false;
                firstInvalidEl = firstInvalidEl || fotoInput;
            } else {
                setFieldValid(fotoInput);
            }
        }

        if (!isValid) {
            showMessage(
                'Validasi gagal: mohon lengkapi field wajib yang ditandai merah.',
                'danger',
                'modal'
            );

            if (firstInvalidEl) {
                firstInvalidEl.focus();
            }
        }

        return isValid;
    }

    // Validasi real-time saat pengguna mengetik di form modal
    nimInput.addEventListener('input', function () {
        const val = this.value.trim();
        if (val.length === 9) {
            setFieldValid(nimInput);
        } else if (nimInput.classList.contains('is-invalid')) {
            setFieldInvalid(
                nimInput,
                nimError,
                val.length === 0
                    ? 'NIM wajib diisi.'
                    : 'NIM harus terdiri dari tepat 9 karakter.'
            );
        }
    });

    namaInput.addEventListener('input', function () {
        if (this.value.trim().length > 0) {
            setFieldValid(namaInput);
        } else if (namaInput.classList.contains('is-invalid')) {
            setFieldInvalid(namaInput, namaError, 'Nama mahasiswa wajib diisi.');
        }
    });

    prodiInput.addEventListener('input', function () {
        if (this.value.trim().length > 0) {
            setFieldValid(prodiInput);
        } else if (prodiInput.classList.contains('is-invalid')) {
            setFieldInvalid(
                prodiInput,
                prodiError,
                'Program studi wajib diisi.'
            );
        }
    });

    // Menghindari HTML injection pada data
    function escapeHTML(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    // Membuat URL foto mahasiswa
    function getFotoURL(namaFoto) {
        if (!namaFoto) {
            return null;
        }

        return `${UPLOAD_URL}/${encodeURIComponent(namaFoto)}`;
    }

    // Menampilkan foto atau placeholder
    function renderFoto(namaFoto) {
        if (namaFoto) {
            const fotoURL = getFotoURL(namaFoto);

            return `
                <img
                    src="${escapeHTML(fotoURL)}"
                    alt="Foto mahasiswa"
                    class="foto-mahasiswa"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >

                <div
                    class="foto-placeholder"
                    style="display:none"
                >
                    Tidak ada foto
                </div>
            `;
        }

        return `
            <div class="foto-placeholder">
                Tidak ada foto
            </div>
        `;
    }

    // Mengambil data mahasiswa dari API
    async function loadMahasiswa() {
        try {
            mahasiswaTable.innerHTML = `
                <tr>
                    <td colspan="${isAdmin ? 6 : 5}" class="text-center">
                        Memuat data...
                    </td>
                </tr>
            `;

            const response = await fetch(
                `${BASE_URL}/mahasiswa`,
                {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }
            );

            const result = await response.json();

            if (!response.ok) {

                if (response.status === 401) {
                    localStorage.clear();
                    window.location.href = 'login.html';
                    return;
                }

                showMessage(
                    result.message || 'Gagal mengambil data mahasiswa',
                    'danger'
                );

                mahasiswaTable.innerHTML = `
                    <tr>
                        <td colspan="${isAdmin ? 6 : 5}" class="text-center text-danger">
                            Gagal mengambil data mahasiswa
                        </td>
                    </tr>
                `;

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
                    <td colspan="${isAdmin ? 6 : 5}" class="text-center text-danger">
                        Gagal menghubungkan ke server
                    </td>
                </tr>
            `;

            showMessage(
                'Gagal menghubungkan ke server',
                'danger'
            );
        }
    }

    // Menampilkan data mahasiswa ke tabel
    function renderMahasiswa() {

        totalMahasiswa.textContent =
            mahasiswaData.length;

        if (mahasiswaData.length === 0) {

            mahasiswaTable.innerHTML = `
                <tr>
                    <td colspan="${isAdmin ? 6 : 5}" class="text-center">
                        Belum ada data mahasiswa
                    </td>
                </tr>
            `;

            return;
        }

        mahasiswaTable.innerHTML = mahasiswaData.map(
            (mahasiswa, index) => {

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

                        <td>
                            ${renderFoto(mahasiswa.foto)}
                        </td>

                        <td>${escapeHTML(mahasiswa.nim)}</td>

                        <td>${escapeHTML(mahasiswa.nama)}</td>

                        <td>${escapeHTML(mahasiswa.prodi)}</td>

                        ${actionButtons}

                    </tr>
                `;
            }
        ).join('');
    }

    // Reset form tambah mahasiswa
    function resetFormTambah() {

        mahasiswaForm.reset();

        resetValidationState();

        mahasiswaId.value = '';

        modalTitle.textContent = 'Tambah Mahasiswa';

        saveButton.textContent = 'Simpan';

        fotoPreview.src = '';

        fotoPreviewContainer.classList.add('d-none');
    }

    // Membuka modal tambah mahasiswa
    addButton.addEventListener('click', function () {
        resetFormTambah();
    });

    // Preview foto ketika file dipilih
    fotoInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {

            fotoInput.classList.remove('is-invalid', 'is-valid');

            fotoPreview.src = '';

            fotoPreviewContainer.classList.add('d-none');

            return;
        }

        // Validasi ukuran file
        if (file.size > 2 * 1024 * 1024) {

            setFieldInvalid(
                fotoInput,
                fotoError,
                'Ukuran foto maksimal 2 MB.'
            );

            showMessage(
                'Ukuran foto maksimal 2 MB',
                'danger',
                'modal'
            );

            this.value = '';

            fotoPreview.src = '';

            fotoPreviewContainer.classList.add('d-none');

            return;
        }

        // Validasi format file
        const formatValid = [
            'image/jpeg',
            'image/png'
        ];

        if (!formatValid.includes(file.type)) {

            setFieldInvalid(
                fotoInput,
                fotoError,
                'Foto harus berformat JPG atau PNG.'
            );

            showMessage(
                'Foto harus berformat JPG atau PNG',
                'danger',
                'modal'
            );

            this.value = '';

            fotoPreview.src = '';

            fotoPreviewContainer.classList.add('d-none');

            return;
        }

        setFieldValid(fotoInput);

        // Menampilkan preview
        const reader = new FileReader();

        reader.onload = function (event) {

            fotoPreview.src = event.target.result;

            fotoPreviewContainer.classList.remove('d-none');
        };

        reader.readAsDataURL(file);
    });

    // Menangani tambah dan edit mahasiswa
    mahasiswaForm.addEventListener('submit', async function (event) {

        event.preventDefault();

        if (!isAdmin) {

            showMessage(
                'Akses ditolak. Hanya admin yang diizinkan.',
                'danger',
                'modal'
            );

            return;
        }

        const id = mahasiswaId.value;

        const isEdit = id !== '';

        const nim = nimInput.value.trim();

        const nama = namaInput.value.trim();

        const prodi = prodiInput.value.trim();

        // File foto
        const fileFoto = fotoInput.files[0];

        // Validasi sisi View sebelum mengirim data ke Controller
        if (!validateViewForm(nim, nama, prodi, fileFoto)) {
            return;
        }

        const url = isEdit
            ? `${BASE_URL}/mahasiswa?id=${id}`
            : `${BASE_URL}/mahasiswa`;

        saveButton.disabled = true;

        saveButton.textContent = 'Menyimpan...';

        try {

            let response;

            //==================================================
            // TAMBAH MAHASISWA
            //==================================================

            if (!isEdit) {

                const formData = new FormData();

                formData.append('nim', nim);

                formData.append('nama', nama);

                formData.append('prodi', prodi);

                if (fileFoto) {
                    formData.append('foto', fileFoto);
                }

                response = await fetch(url, {
                    method: 'POST',

                    headers: {
                        'Authorization': `Bearer ${token}`
                    },

                    body: formData
                });

            }

            //==================================================
            // EDIT MAHASISWA
            //==================================================

            else {

                // Jika foto baru dipilih
                if (fileFoto) {

                    const formData = new FormData();

                    formData.append('nim', nim);

                    formData.append('nama', nama);

                    formData.append('prodi', prodi);

                    formData.append('foto', fileFoto);

                    response = await fetch(url, {
                        method: 'PUT',

                        headers: {
                            'Authorization': `Bearer ${token}`
                        },

                        body: formData
                    });

                }

                // Jika tidak mengganti foto
                else {

                    const data = {
                        nim: nim,
                        nama: nama,
                        prodi: prodi
                    };

                    response = await fetch(url, {
                        method: 'PUT',

                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },

                        body: JSON.stringify(data)
                    });
                }
            }

            const result = await response.json();

            if (!response.ok || !result.success) {

                let pesanError =
                    result.message ||
                    (isEdit
                        ? 'Gagal memperbarui data mahasiswa'
                        : 'Gagal menambahkan data mahasiswa');

                if (result.errors && typeof result.errors === 'object') {
                    const detailErrors = Object.values(result.errors).join(', ');
                    if (detailErrors) {
                        pesanError += ` (${detailErrors})`;
                    }
                }

                // Tampilkan notifikasi gagal di dalam modal agar user bisa langsung memperbaiki
                showMessage(
                    pesanError,
                    'danger',
                    'modal'
                );

                return;
            }

            // Menutup modal
            modal.hide();

            mahasiswaForm.reset();

            resetValidationState();

            fotoPreview.src = '';

            fotoPreviewContainer.classList.add('d-none');

            // Menampilkan notifikasi visual berhasil di dashboard
            showMessage(
                result.message ||
                    (isEdit
                        ? 'Data mahasiswa berhasil diperbarui'
                        : 'Data mahasiswa berhasil ditambahkan'),
                'success',
                'dashboard'
            );

            // Memuat ulang data mahasiswa
            await loadMahasiswa();

        } catch (error) {

            console.error('Error:', error);

            showMessage(
                'Terjadi kesalahan koneksi saat menyimpan data',
                'danger',
                'modal'
            );

        } finally {

            saveButton.disabled = false;

            saveButton.textContent = isEdit
                ? 'Update'
                : 'Simpan';
        }

    });

    // Menangani tombol Edit dan Hapus
    mahasiswaTable.addEventListener('click', async function (event) {

        // Mengambil tombol yang diklik
        const target = event.target.closest('button');

        if (!target) {
            return;
        }

        //==================================================
        // TOMBOL EDIT
        //==================================================

        if (target.classList.contains('editButton')) {

            if (!isAdmin) {
                return;
            }

            const id = target.dataset.id;

            const mahasiswa = mahasiswaData.find(
                item => String(item.id) === String(id)
            );

            if (!mahasiswa) {

                showMessage(
                    'Data mahasiswa tidak ditemukan',
                    'danger',
                    'dashboard'
                );

                return;
            }

            resetValidationState();

            // Mengisi form
            mahasiswaId.value = mahasiswa.id;

            nimInput.value = mahasiswa.nim;

            namaInput.value = mahasiswa.nama;

            prodiInput.value = mahasiswa.prodi;

            // Reset input foto
            fotoInput.value = '';

            // Menampilkan foto lama
            if (mahasiswa.foto) {

                fotoPreview.src =
                    getFotoURL(mahasiswa.foto);

                fotoPreviewContainer.classList.remove('d-none');

            } else {

                fotoPreview.src = '';

                fotoPreviewContainer.classList.add('d-none');
            }

            modalTitle.textContent = 'Edit Mahasiswa';

            saveButton.textContent = 'Update';

            modal.show();
        }

        //==================================================
        // TOMBOL HAPUS
        //==================================================

        if (target.classList.contains('deleteButton')) {

            if (!isAdmin) {
                return;
            }

            const id = target.dataset.id;

            const konfirmasi = confirm(
                'Apakah kamu yakin ingin menghapus data mahasiswa ini?'
            );

            if (!konfirmasi) {
                return;
            }

            try {

                const response = await fetch(
                    `${BASE_URL}/mahasiswa?id=${id}`,
                    {
                        method: 'DELETE',

                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    }
                );

                const result = await response.json();

                if (!response.ok || !result.success) {

                    showMessage(
                        result.message || 'Gagal menghapus data mahasiswa',
                        'danger',
                        'dashboard'
                    );

                    return;
                }

                showMessage(
                    result.message || 'Data mahasiswa berhasil dihapus',
                    'success',
                    'dashboard'
                );

                await loadMahasiswa();

            } catch (error) {

                console.error('Error:', error);

                showMessage(
                    'Terjadi kesalahan saat menghapus data mahasiswa',
                    'danger',
                    'dashboard'
                );
            }
        }

    });

    //==================================================
    // LOGOUT
    //==================================================

    logoutButton.addEventListener('click', function () {

        const konfirmasi = confirm(
            'Apakah kamu yakin ingin logout?'
        );

        if (konfirmasi) {

            localStorage.removeItem('token');

            localStorage.removeItem('role');

            localStorage.removeItem('username');

            // Menghapus guard inisialisasi
            window.__dashboardAcara26Initialized = false;

            window.location.href = 'login.html';
        }

    });

    // Reset form ketika modal ditutup
    mahasiswaModal.addEventListener(
        'hidden.bs.modal',
        function () {

            mahasiswaForm.reset();

            resetValidationState();

            mahasiswaId.value = '';

            modalTitle.textContent = 'Tambah Mahasiswa';

            saveButton.textContent = 'Simpan';

            fotoPreview.src = '';

            fotoPreviewContainer.classList.add('d-none');
        }
    );

    // Memuat data mahasiswa ketika dashboard dibuka
    loadMahasiswa();

})();