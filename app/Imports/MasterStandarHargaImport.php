<?php

namespace App\Imports;

use App\Models\MasterStandarHarga;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class MasterStandarHargaImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $jenisStandar;

    // Kita terima parameter 'jenis' (SSH/SBU/dll) dari Form Upload nanti
    public function __construct($jenisStandar)
    {
        $this->jenisStandar = $jenisStandar;
    }

    public function model(array $row)
    {
        // Pastikan kolom 'uraian_barang' ada isinya, kalau kosong skip
        if (!isset($row['uraian_barang'])) {
            return null;
        }

        return new MasterStandarHarga([
            'jenis_standar'          => $this->jenisStandar,
            // Mapping sesuai Header di CSV Anda (Otomatis dibaca lowercase oleh library)
            'kode_kelompok_barang'   => $row['kode_kelompok_barang'] ?? null,
            'uraian_kelompok_barang' => $row['uraian_kelompok_barang'] ?? null,
            'id_standar_harga'       => $row['id_standar_harga'] ?? null,
            'kode_barang'            => $row['kode_barang'],
            'uraian_barang'          => $row['uraian_barang'],
            'spesifikasi'            => $row['spesifikasi'] ?? null,
            'satuan'                 => $row['satuan'] ?? null,
            'harga_satuan'           => $row['harga_satuan'] ?? 0,
            'kode_rekening'          => $row['kode_rekening'] ?? null,
        ]);
    }

    // Baca per 1000 baris agar RAM tidak jebol
    public function chunkSize(): int
    {
        return 1000;
    }

    // Insert ke database per 1000 baris (Cepat!)
    public function batchSize(): int
    {
        return 1000;
    }
}