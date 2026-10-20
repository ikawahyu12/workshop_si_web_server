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

        <div class="mb-4">

            <h1>Edit Mahasiswa</h1>

            <p class="text-muted">
                Ubah data mahasiswa
            </p>

        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form
                    action="/workshop_si_web_server/acara8/public/mahasiswa/edit?id=<?= $mahasiswa['id'] ?>"
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
                            value="<?= htmlspecialchars($mahasiswa['nim']) ?>"
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
                            value="<?= htmlspecialchars($mahasiswa['nama']) ?>"
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
                            value="<?= htmlspecialchars($mahasiswa['email']) ?>"
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
                                <?= $mahasiswa['prodi_id'] == 1 ? 'selected' : '' ?>>
                                Teknik Informatika
                            </option>

                            <option value="2"
                                <?= $mahasiswa['prodi_id'] == 2 ? 'selected' : '' ?>>
                                Manajemen Informatika
                            </option>

                            <option value="3"
                                <?= $mahasiswa['prodi_id'] == 3 ? 'selected' : '' ?>>
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
                            value="<?= htmlspecialchars($mahasiswa['angkatan']) ?>"
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
                            href="/workshop_si_web_server/acara8/public/mahasiswa"
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