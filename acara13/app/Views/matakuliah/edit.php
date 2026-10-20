<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Mata Kuliah</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<div class="container mt-4">

    <div class="mb-4">
        <h1>Edit Mata Kuliah</h1>
        <p class="text-muted">
            Ubah data mata kuliah
        </p>
    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <form
                action="/workshop_si_web_server/acara8/public/matakuliah/edit?id=<?= $matakuliah['id'] ?>"
                method="POST"
            >

                <div class="mb-3">
                    <label for="kode" class="form-label">
                        Kode Mata Kuliah
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="kode"
                        name="kode"
                        value="<?= htmlspecialchars($matakuliah['kode']) ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama Mata Kuliah
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nama"
                        name="nama"
                        value="<?= htmlspecialchars($matakuliah['nama']) ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="sks" class="form-label">
                        SKS
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="sks"
                        name="sks"
                        min="1"
                        max="6"
                        value="<?= htmlspecialchars($matakuliah['sks']) ?>"
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
                        href="/workshop_si_web_server/acara8/public/matakuliah"
                        class="btn btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>