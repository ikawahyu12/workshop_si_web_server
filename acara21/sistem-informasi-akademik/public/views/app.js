const BASE_URL = 'http://localhost/sistem-informasi-akademik/public'; 
  
async function muatDataMahasiswa() { 
    try { 
        const response = await fetch(`${BASE_URL}/mahasiswa`); 
        const hasil = await response.json(); 
        const tbody = document.querySelector('#tabel-mahasiswa tbody'); 
        tbody.innerHTML = ''; 
        hasil.data.forEach((mhs) => { 
            tbody.innerHTML += 
`<tr><td>${mhs.nim}</td><td>${mhs.nama}</td><td>${mhs.prodi}</td></tr>`; 
        }); 
    } catch (error) { 
        console.error('Gagal memuat data:', error); 
    } 
} 
  
document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
