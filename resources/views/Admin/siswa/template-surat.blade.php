<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $jenisSurat }} - {{ $siswa->name }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 40px; }
        .kop-surat { text-align: center; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 30px; }
        .kop-surat h1 { margin: 0; font-size: 24px; }
        .kop-surat p { margin: 5px 0; }
        .isi-surat { margin-top: 30px; }
        .isi-surat p { line-height: 1.6; text-align: justify; }
        .ttd { margin-top: 80px; text-align: right; }
        .ttd .nama { margin-top: 60px; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h1>SEKOLAH MENENGAH ATAS HARAPAN BANGSA</h1>
        <p>Jalan Pendidikan No. 123, Kota Malang, Jawa Timur</p>
        <p>Telepon: (0341) 123456 - Email: info@smaharapan.sch.id</p>
    </div>

    <h2 style="text-align: center; text-decoration: underline;">{{ strtoupper($jenisSurat) }}</h2>
    <p style="text-align: center;">Nomor: 123/SP/IX/2025</p>

    <div class="isi-surat">
        <p>Yang bertanda tangan di bawah ini, Kepala Sekolah SMA Harapan Bangsa, memberikan surat peringatan kepada:</p>
        
        <table>
            <tr>
                <td style="width: 150px;">Nama</td>
                <td>: {{ $siswa->name }}</td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>: {{ $siswa->biodataSiswa->nis ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: {{ $siswa->biodataSiswa->kelas->nama_kelas ?? 'N/A' }}</td>
            </tr>
        </table>

        <p>
            Berdasarkan catatan Bimbingan Konseling, siswa yang bersangkutan telah mencapai total <strong>{{ $totalPoin }} poin</strong> pelanggaran tata tertib sekolah. 
            Dengan ini, sekolah memberikan <strong>{{ $jenisSurat }}</strong> sebagai bentuk pembinaan.
        </p>
        <p>
            Kami berharap siswa yang bersangkutan dapat memperbaiki sikap dan perilakunya serta tidak mengulangi pelanggaran di kemudian hari.
        </p>
        <p>
            Demikian surat peringatan ini kami sampaikan untuk dapat diperhatikan.
        </p>
    </div>

    <div class="ttd">
        <p>Malang, {{ $tanggalCetak }}</p>
        <p>Kepala Sekolah,</p>
        <div class="nama">
            <p><strong>( Nama Kepala Sekolah )</strong></p>
        </div>
    </div>
</body>
</html>