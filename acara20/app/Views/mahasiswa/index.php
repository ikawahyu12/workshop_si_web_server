<?php

// ==============================
// SESSION
// ==============================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ==============================
// FLASH MESSAGE
// ==============================

$flash = $_SESSION['flash'] ?? null;


// Hapus flash setelah diambil
unset($_SESSION['flash']);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Daftar Mahasiswa</title>


    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body>

<div class="container mt-4">


    <!-- ============================== -->
    <!-- FLASH MESSAGE -->
    <!-- ============================== -->

    <?php if ($flash): ?>

        <div
            class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show"
            role="alert"
        >
            <?= $flash['message'] ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>

    <?php endif; ?>


    <!-- ============================== -->
    <!-- HEADER -->
    <!-- ============================== -->

    <div
        class="d-flex justify-content-between align-items-center mb-4"
    >

        <div>

            <h1>
                Daftar Mahasiswa
            </h1>

            <p class="text-muted">
                Data mahasiswa SI Akademik
            </p>

        </div>


        <a
            href="/workshop_si_web_server/acara14/public/mahasiswa/create"
            class="btn btn-primary"
        >
            Tambah Mahasiswa
        </a>

    </div>


    <!-- ============================== -->
    <!-- SEARCH -->
    <!-- ============================== -->

    <form
        method="GET"
        action="/workshop_si_web_server/acara14/public/mahasiswa"
        class="mb-3"
    >

        <div class="input-group">

            <input
                type="text"
                name="keyword"
                class="form-control"
                placeholder="Cari berdasarkan NIM atau nama..."
                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
            >

            <button
                type="submit"
                class="btn btn-primary"
            >
                Cari
            </button>

            <a
                href="/workshop_si_web_server/acara14/public/mahasiswa"
                class="btn btn-secondary"
            >
                Reset
            </a>

        </div>

    </form>


    <!-- ============================== -->
    <!-- TABLE -->
    <!-- ============================== -->

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-striped align-middle"
                >

                    <thead class="table-dark">

                        <tr>

                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th>Status</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (empty($mahasiswa)): ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center"
                            >
                                Data mahasiswa tidak ditemukan.
                            </td>

                        </tr>

                    <?php else: ?>


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
                                    <?= htmlspecialchars($mhs['prodi_nama']) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($mhs['angkatan']) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($mhs['status'] ?? 'Aktif') ?>
                                </td>


                                <td>

                                    <a
                                        href="/workshop_si_web_server/acara14/public/mahasiswa/edit?id=<?= $mhs['id'] ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>


                                    <a
                                        href="/workshop_si_web_server/acara14/public/mahasiswa/delete?id=<?= $mhs['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?')"
                                    >
                                        Hapus
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


</div>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>