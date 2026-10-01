<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Mahasiswa</title>

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
                <h1>Daftar Mahasiswa</h1>

                <p class="text-muted">
                    Data mahasiswa SI Akademik
                </p>
            </div>

            <a
                href="/workshop_si_web_server/acara7/public/mahasiswa/create"
                class="btn btn-primary"
            >
                Tambah Mahasiswa
            </a>

        </div>


        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped align-middle">

                        <thead class="table-dark">

                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Program Studi</th>
                                <th>Angkatan</th>
                                <th>Status</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($mahasiswa as $index => $mhs): ?>

                                <tr>

                                    <td>
                                        <?= $index + 1 ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['nim']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['nama']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['prodi_id']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['angkatan']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($mhs['status']) ?>
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