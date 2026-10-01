<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1>Daftar Mahasiswa</h1>
            <p class="text-muted">
                Data mahasiswa SI Akademik
            </p>
        </div>

        <a href="#" class="btn btn-primary">
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
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($mahasiswa as $index => $mhs): ?>

                            <tr>

                                <td><?= $index + 1 ?></td>

                                <td>
                                    <?= htmlspecialchars($mhs->getNim()) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs->getNama()) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs->getProdi()) ?>
                                </td>

                                <td>
                                    <?= $mhs->getAngkatan() ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>