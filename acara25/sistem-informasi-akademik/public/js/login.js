// URL dasar API Acara 25
const BASE_URL =
    'http://localhost/workshop_si_web_server/acara25/sistem-informasi-akademik/public';

// Mengambil elemen form
const loginForm = document.getElementById('loginForm');
const alertMessage = document.getElementById('alertMessage');
const loginButton = document.getElementById('loginButton');

// Menampilkan pesan
function showMessage(message, type = 'danger') {
    alertMessage.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button type="button" class="btn-close"
                data-bs-dismiss="alert"></button>
        </div>
    `;
}

// Proses login
loginForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;

    loginButton.disabled = true;
    loginButton.textContent = 'Memproses...';

    try {
        const response = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            showMessage(result.message || 'Login gagal');
            return;
        }

        // Menyimpan token dan role
        localStorage.setItem('token', result.data.token);
        localStorage.setItem('role', result.data.role);
        localStorage.setItem('username', username);

        showMessage('Login berhasil!', 'success');

        // Redirect ke dashboard
        setTimeout(() => {
            window.location.href = 'dashboard.html';
        }, 500);

    } catch (error) {
        console.error('Error:', error);

        showMessage(
            'Tidak dapat terhubung ke server. Pastikan server berjalan.'
        );

    } finally {
        loginButton.disabled = false;
        loginButton.textContent = 'Login';
    }
});