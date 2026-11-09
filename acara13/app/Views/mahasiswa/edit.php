<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash = $_SESSION['flash'] ?? null;
$oldInput = $_SESSION['mahasiswa_old_input'] ?? [];
unset($_SESSION['flash'], $_SESSION['mahasiswa_old_input']);

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Mahasiswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

    <div class="container mt-4">

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>" role="alert">
                <?= nl2br(htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8')) ?>
            </div>
        <?php endif; ?>

        <div class="mb-4">

            <h1>Edit Mahasiswa</h1>

            <p class="text-muted">
                Ubah data mahasiswa
            </p>

        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form
                    action="/workshop_si_web_server/acara13/public/mahasiswa/edit?id=<?= $mahasiswa['id'] ?>"
                    method="POST"
                >

                    <div class="mb-3">

                        <label for="nim" class="form-label">
                            NIM
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nim"
                            name="nim"
                            value="<?= htmlspecialchars($oldInput['nim'] ?? $mahasiswa['nim'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label for="nama" class="form-label">
                            Nama
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nama"
                            name="nama"
                            value="<?= htmlspecialchars($oldInput['nama'] ?? $mahasiswa['nama'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($oldInput['email'] ?? $mahasiswa['email'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label for="prodi_id" class="form-label">
                            Program Studi
                        </label>

                        <select
                            class="form-select"
                            id="prodi_id"
                            name="prodi_id"
                            required
                        >

                            <option value="1"
                                <?= (string) ($oldInput['prodi_id'] ?? $mahasiswa['prodi_id']) === '1' ? 'selected' : '' ?>>
                                Teknik Informatika
                            </option>

                            <option value="2"
                                <?= (string) ($oldInput['prodi_id'] ?? $mahasiswa['prodi_id']) === '2' ? 'selected' : '' ?>>
                                Manajemen Informatika
                            </option>

                            <option value="3"
                                <?= (string) ($oldInput['prodi_id'] ?? $mahasiswa['prodi_id']) === '3' ? 'selected' : '' ?>>
                                Teknik Komputer dan Komunikasi
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label for="angkatan" class="form-label">
                            Angkatan
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="angkatan"
                            name="angkatan"
                            value="<?= htmlspecialchars($oldInput['angkatan'] ?? $mahasiswa['angkatan'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                    </div>

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Simpan Perubahan
                        </button>

                        <a
                            href="/workshop_si_web_server/acara13/public/mahasiswa"
                            class="btn btn-secondary"
                        >
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>

</html>