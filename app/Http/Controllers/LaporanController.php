<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Realisasi;
use App\Models\Sekolah; // <--- PENTING: Import Model Sekolah
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function cetakPerencanaan($id)
    {
        // 1. Ambil data anggaran beserta detailnya
        $anggaran = Anggaran::with(['details', 'kegiatan'])->findOrFail($id);

        // 2. AMBIL DATA SEKOLAH (Ambil yang pertama saja karena Single Tenant)
        $sekolah = Sekolah::first(); 
        
        // Fallback: Jika profil sekolah belum diisi, pakai data dummy agar tidak error
        if (!$sekolah) {
            $sekolah = new Sekolah([
                'nama_sekolah' => 'BELUM DISETTING',
                'alamat' => 'Alamat Sekolah Belum Diisi',
                'kabupaten' => 'INDRAMAYU',
                'telepon' => '-',
                'logo' => null,
            ]);
        }

        // 3. Load view blade dan oper datanya ($anggaran DAN $sekolah)
        $pdf = Pdf::loadView('laporan.perencanaan', compact('anggaran', 'sekolah'))
            ->setPaper('a4', 'portrait');

        // 4. Stream PDF
        return $pdf->stream('Rencana_Anggaran_' . $anggaran->kegiatan->kode_sub_kegiatan . '.pdf');
    }

    public function cetakRealisasi($id)
    {
        // 1. Ambil data realisasi beserta detailnya
        $realisasi = Realisasi::with(['details', 'kegiatan'])->findOrFail($id);

        // 2. AMBIL DATA SEKOLAH (Sama seperti di atas)
        $sekolah = Sekolah::first(); 
        
        if (!$sekolah) {
            $sekolah = new Sekolah([
                'nama_sekolah' => 'BELUM DISETTING',
                'alamat' => 'Alamat Sekolah Belum Diisi',
                'kabupaten' => 'INDRAMAYU',
                'telepon' => '-',
                'logo' => null,
            ]);
        }

        // 3. Load view blade dan oper datanya ($realisasi DAN $sekolah)
        $pdf = Pdf::loadView('laporan.realisasi', compact('realisasi', 'sekolah'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Realisasi_' . $realisasi->kegiatan->kode_sub_kegiatan . '.pdf');
    }
}