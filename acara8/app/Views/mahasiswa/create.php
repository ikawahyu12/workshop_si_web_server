<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tambah Mahasiswa</title>

    <!-- Bootstrap 5 CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

    <div class="container mt-4">

        <div class="mb-4">

            <h1>Tambah Mahasiswa</h1>

            <p class="text-muted">
                Tambahkan data mahasiswa baru
            </p>

        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form
                    action="/workshop_si_web_server/acara8/public/mahasiswa/create"
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

                            <option value="">
                                -- Pilih Program Studi --
                            </option>

                            <option value="1">
                                Teknik Informatika
                            </option>

                            <option value="2">
                                Manajemen Informatika
                            </option>

                            <option value="3">
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
                            required
                        >

                    </div>

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Simpan
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