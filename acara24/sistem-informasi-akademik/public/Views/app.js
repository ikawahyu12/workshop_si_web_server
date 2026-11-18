const BASE_URL = 'http://localhost/workshop_si_web_server/acara24/sistem-informasi-akademik/public';
function escapeHTML(teks) {
    const div = document.createElement('div');
    div.innerText = teks;
    return div.innerHTML;
}

function renderTabel(data) {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3">Belum ada data</td></tr>';
        return;
    }

    tbody.innerHTML = data.map((mhs) => `
        <tr>
            <td>${escapeHTML(mhs.nim)}</td>
            <td>${escapeHTML(mhs.nama)}</td>
            <td>${escapeHTML(mhs.prodi)}</td>
        </tr>
    `).join('');
}

async function muatDataMahasiswa() {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');
    tbody.innerHTML = '<tr><td colspan="3">Memuat data...</td></tr>';

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        if (!response.ok) {
            throw new Error('Server merespons dengan status ' + response.status);
        }
        const hasil = await response.json();
        renderTabel(hasil.data);
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tbody.innerHTML = `<tr><td colspan="3">Gagal memuat data: ${escapeHTML(error.message)}</td></tr>`;
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);