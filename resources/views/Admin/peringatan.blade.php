<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Peringatan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 0; }
        .content p { line-height: 1.6; }
        .content table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .content th, .content td { border: 1px solid #ddd; padding: 8px; }
        .signature { margin-top: 60px; }
        .signature .signer { float: right; width: 200px; text-align: center; }
        .signature .signer p { margin-bottom: 60px; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Ganti dengan Kop Surat Sekolahmu --}}
            <h1>KOP SURAT SEKOLAH ANDA</h1>
            <p>Alamat Sekolah, Kota, Kode Pos | Telepon: (0XX) XXX-XXXX</p>
            <hr style="margin-top: 10px;">
        </div>

        <div class="content">
            <h2 style="text-align: center; text-decoration: underline;">SURAT PERINGATAN</h2>
            <p style="text-align: center;">Nomor: ....../SP/SMK-XX/IX/2025</p>

            <p>Yang bertanda tangan di bawah ini, Kepala Sekolah SMK [Nama Sekolah Anda], memberikan surat peringatan kepada siswa yang data dirinya tersebut di bawah ini:</p>

            <table>
                <tr>
                    <td style="width: 150px;"><strong>Nama Lengkap</strong></td>
                    <td>: {{ $siswa->name }}</td>
                </tr>
                <tr>
                    <td><strong>NIS</strong></td>
                    <td>: {{ $siswa->biodataSiswa->nis ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Kelas</strong></td>
                    <td>: {{ $siswa->biodataSiswa->kelas->nama_kelas ?? 'N/A' }}</td>
                </tr>
            </table>

            <p>Surat peringatan ini diberikan sehubungan dengan akumulasi poin pelanggaran tata tertib sekolah yang telah mencapai <strong>{{ $totalPoin }} poin</strong>. Berdasarkan catatan kami, pelanggaran-pelanggaran tersebut antara lain:</p>

            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswa->pelanggaranSiswa as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $item->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ $item->pelanggaran->poin ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">Tidak ada data pelanggaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <p>Dengan diberikannya surat ini, kami berharap siswa yang bersangkutan dapat memperbaiki sikap dan perilakunya serta tidak mengulangi pelanggaran yang sama di kemudian hari. </p>
            <p>Demikian surat peringatan ini kami sampaikan untuk dapat diperhatikan. Atas perhatian Bapak/Ibu, kami ucapkan terima kasih.</p>
        </div>

        <div class="signature clearfix">
            <div class="signer">
                <p>Malang, {{ $tanggalCetak }}<br>Kepala Sekolah,</p>

                <p><strong>(_______________________)</strong><br>NIP. .......................</p>
            </div>
        </div>
    </div>
</body>
</html>