<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Program Studi</title>

    <!-- Bootstrap 5 CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h1>Daftar Program Studi</h1>

                <p class="text-muted">
                    Data Program Studi SI Akademik
                </p>
            </div>

            <a
                href="/workshop_si_web_server/acara8/public/prodi/create"
                class="btn btn-primary"
            >
                Tambah Prodi
            </a>

        </div>


        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped align-middle">

                        <thead class="table-dark">

                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Program Studi</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($prodi as $index => $p): ?>

                                <tr>

                                    <td>
                                        <?= $index + 1 ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($p['kode']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($p['nama']) ?>
                                    </td>

                                    <td>

                                        <a
                                            href="/workshop_si_web_server/acara8/public/prodi/edit?id=<?= $p['id'] ?>"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="/workshop_si_web_server/acara8/public/prodi/delete?id=<?= $p['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah kamu yakin ingin menghapus data prodi ini?')"
                                        >
                                            Hapus
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>