<?php

require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class MahasiswaService
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    public function __construct(
        MahasiswaRepository $repo,
        ProdiRepository $prodiRepo
    ) {
        $this->repo = $repo;
        $this->prodiRepo = $prodiRepo;
    }

    public function getAllMahasiswa(string $keyword = ''): array
    {
        return $this->repo->all();
    }

    public function getMahasiswaById(int $id): ?array
    {
        return $this->repo->find($id);
    }

    public function createMahasiswa(array $input): array
    {
        $errors = $this->validate($input);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $success = $this->repo->create(
            $input['nim'],
            $input['nama'],
            $input['email'],
            (int) $input['prodi_id'],
            (int) $input['angkatan']
        );

        return [
            'success' => $success,
            'errors' => $success ? [] : ['database' => 'Data gagal disimpan.']
        ];
    }

    public function updateMahasiswa(int $id, array $input): array
    {
        $errors = $this->validate($input, $id);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $success = $this->repo->update(
            $id,
            $input['nim'],
            $input['nama'],
            $input['email'],
            (int) $input['prodi_id'],
            (int) $input['angkatan']
        );

        return [
            'success' => $success,
            'errors' => $success ? [] : ['database' => 'Data gagal diperbarui.']
        ];
    }

    public function deleteMahasiswa(int $id): bool
    {
        return $this->repo->delete($id);
    }

    private function validate(array $input, ?int $id = null): array
    {
        $errors = [];

        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');
        $prodi_id = (int) ($input['prodi_id'] ?? 0);
        $angkatan = (int) ($input['angkatan'] ?? 0);

        if ($nim === '') {
            $errors['nim'] = 'NIM wajib diisi';
        }

        if ($nama === '') {
            $errors['nama'] = 'Nama wajib diisi';
        }

        if ($email === '') {
            $errors['email'] = 'Email wajib diisi';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        }

        if ($prodi_id <= 0) {
            $errors['prodi_id'] = 'Program studi wajib dipilih';
        }

        if ($angkatan <= 0) {
            $errors['angkatan'] = 'Angkatan wajib diisi';
        }

        if ($nim !== '') {
            $nimExists = $this->repo->existsByNim($nim);

            if ($nimExists) {
                $existing = $this->repo->findByNim($nim);

                if (!$id || !$existing || (int) $existing['id'] !== $id) {
                    $errors['nim'] = 'NIM sudah terdaftar';
                }
            }
        }

        return $errors;
    }
}