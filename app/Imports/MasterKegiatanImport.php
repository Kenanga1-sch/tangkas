<?php

namespace App\Imports;

use App\Models\MasterKegiatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterKegiatanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip jika baris kosong
        if (!isset($row['kode_sub_kegiatan'])) {
            return null;
        }

        return new MasterKegiatan([
            // Library Excel otomatis mengubah header "Standar Pendidikan" jadi "standar_pendidikan"
            'standar_pendidikan'  => $row['standar_pendidikan'],
            'kode_kegiatan'       => $row['kode_kegiatan'],
            'uraian_kegiatan'     => $row['uraian_kegiatan'],
            'kode_sub_kegiatan'   => $row['kode_sub_kegiatan'],
            'uraian_sub_kegiatan' => $row['uraian_sub_kegiatan'],
        ]);
    }
}