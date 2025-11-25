<!DOCTYPE html>
<html>
<head>
    <title>Rencana Anggaran</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        
        /* Kop Surat */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double black; padding-bottom: 10px; position: relative; min-height: 80px; }
        .header img { width: 70px; position: absolute; left: 20px; top: 5px; }
        .header h2, .header h3, .header p { margin: 0; }
        .header h2 { font-size: 16px; }
        .header h3 { font-size: 14px; }
        
        /* Informasi Kegiatan */
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { vertical-align: top; padding: 2px; }
        .label { width: 150px; font-weight: bold; }
        
        /* Judul Dokumen */
        .judul { text-align: center; font-weight: bold; margin: 15px 0; text-decoration: underline; }
        
        /* Tabel Rincian */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid black; padding: 5px; }
        .data-table th { background-color: #f0f0f0; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Tanda Tangan */
        .ttd-wrapper { width: 100%; margin-top: 30px; }
        .ttd-box { width: 40%; float: left; text-align: center; }
        .ttd-box.right { float: right; }
        .ttd-space { height: 70px; }
    </style>
</head>
<body>

    <!-- KOP SURAT DINAMIS -->
    <div class="header">
        @if(isset($sekolah) && $sekolah->logo)
            <img src="{{ public_path('storage/' . $sekolah->logo) }}" alt="Logo">
        @endif

        <h3>PEMERINTAH KABUPATEN {{ strtoupper($sekolah->kabupaten ?? 'INDRAMAYU') }}</h3>
        <h3>DINAS PENDIDIKAN DAN KEBUDAYAAN</h3>
        <h2>{{ strtoupper($sekolah->nama_sekolah ?? 'NAMA SEKOLAH BELUM DISET') }}</h2>
        <p>{{ $sekolah->alamat ?? 'Alamat belum diisi' }} @if($sekolah->telepon) Telp. {{ $sekolah->telepon }} @endif</p>
        <p>{{ strtoupper($sekolah->kabupaten ?? 'INDRAMAYU') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">PROGRAM</td>
            <td>: {{ $anggaran->kegiatan->standar_pendidikan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">SUB PROGRAM</td>
            <td>: Pemeliharaan Sarana dan Prasarana Sekolah</td>
        </tr>
        <tr>
            <td class="label">KODE KEGIATAN</td>
            <td>: {{ $anggaran->kegiatan->kode_kegiatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NAMA KEGIATAN</td>
            <td>: {{ $anggaran->kegiatan->uraian_sub_kegiatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">WAKTU</td>
            <td>: {{ $anggaran->bulan }} {{ $anggaran->tahun }}</td>
        </tr>
        <tr>
            <td class="label">JUMLAH ANGGARAN</td>
            <td>: Rp {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="judul">
        RINCIAN RENCANA ANGGARAN PER KEGIATAN<br>
        ANGGARAN DANA BOSP TAHUN {{ $anggaran->tahun }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">NO</th>
                <th>URAIAN</th>
                <th>QTY</th>
                <th>SATUAN</th>
                <th>HARGA</th>
                <th>JUMLAH HARGA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggaran->details as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <b>{{ $item->uraian }}</b>
                    @if($item->spesifikasi)
                        <br><small><i>{{ $item->spesifikasi }}</i></small>
                    @endif
                </td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-center">{{ $item->satuan }}</td>
                <td class="text-right">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL</th>
                <th class="text-right">Rp {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- TANDA TANGAN DINAMIS -->
    <div class="ttd-wrapper">
        <div class="ttd-box">
            Mengetahui,<br>
            Kepala Sekolah
            <div class="ttd-space"></div>
            <!-- Gunakan nama dari Anggaran dulu, kalau kosong baru ambil dari Master Sekolah -->
            <b><u>{{ $anggaran->nama_kepala_sekolah ?? $sekolah->nama_kepala_sekolah }}</u></b><br>
            NIP. {{ $anggaran->nip_kepala_sekolah ?? $sekolah->nip_kepala_sekolah }}
        </div>

        <div class="ttd-box right">
            {{ ucwords(strtolower($sekolah->kabupaten ?? 'Indramayu')) }}, ...................... {{ $anggaran->tahun }}<br>
            Bendahara Sekolah
            <div class="ttd-space"></div>
            <b><u>{{ $anggaran->nama_bendahara ?? $sekolah->nama_bendahara }}</u></b><br>
            NIP. {{ $anggaran->nip_bendahara ?? $sekolah->nip_bendahara }}
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>