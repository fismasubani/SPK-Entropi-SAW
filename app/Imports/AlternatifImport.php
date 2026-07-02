<?php

namespace App\Imports;

use App\Alternatif;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlternatifImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $nama = $row['nama_alternatif'] ?? $row['nama alternatif'] ?? $row['nama'] ?? null;

        if (!$nama || trim($nama) === '') {
            return null; // skip baris kosong
        }

        return new Alternatif([
            'nama_alternatif' => trim($nama),
        ]);
    }
}
