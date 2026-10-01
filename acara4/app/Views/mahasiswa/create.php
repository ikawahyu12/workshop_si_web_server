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

    <h1 class="mb-4">Tambah Mahasiswa</h1>

    <form action="#" method="POST">

        <div class="mb-3">
            <label for="nim" class="form-label">
                NIM
            </label>

            <input
                type="text"
                class="form-control"
                id="nim"
                name="nim"
                placeholder="Masukkan NIM"
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
                placeholder="Masukkan nama mahasiswa"
            >
        </div>

        <div class="mb-3">
            <label for="prodi" class="form-label">
                Program Studi
            </label>

            <input
                type="text"
                class="form-control"
                id="prodi"
                name="prodi"
                placeholder="Masukkan program studi"
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan
        </button>

        <a href="index.php" class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>