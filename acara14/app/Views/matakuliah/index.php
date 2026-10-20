<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Mata Kuliah</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1>Daftar Mata Kuliah</h1>
            <p class="text-muted">
                Data Mata Kuliah SI Akademik
            </p>
        </div>

        <a
            href="/workshop_si_web_server/acara8/public/matakuliah/create"
            class="btn btn-primary"
        >
            Tambah Mata Kuliah
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
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($matakuliah as $index => $mk): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mk['kode']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mk['nama']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mk['sks']) ?>
                                </td>

                                <td>

                                    <a
                                        href="/workshop_si_web_server/acara8/public/matakuliah/edit?id=<?= $mk['id'] ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="/workshop_si_web_server/acara8/public/matakuliah/delete?id=<?= $mk['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah kamu yakin ingin menghapus data mata kuliah ini?')"
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

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>