<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Panggilan Orang Tua - {{ $siswa->name }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 40px; font-size: 12pt; }
        .kop-surat { text-align: center; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h1 { margin: 0; font-size: 18pt; }
        .kop-surat p { margin: 0; font-size: 11pt; }
        .tabel-info { border-collapse: collapse; width: 100%; margin: 20px 0; }
        .tabel-info td { padding: 4px; }
        .isi-surat { margin-top: 20px; }
        .penutup { margin-top: 40px; }
        .signature { margin-top: 80px; text-align: right; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h1>SEKOLAH MENENGAH KEJURUAN ABC</h1>
        <p>Jl. Pendidikan No. 123, Kota Malang, Jawa Timur | Telp: (0341) 123456</p>
    </div>

    <table>
        <tr>
            <td>Nomor</td>
            <td>: 123/SP/SMK-ABC/IX/2025</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>: Panggilan Orang Tua/Wali Murid</td>
        </tr>
    </table>

    <div class="isi-surat">
        <p>Yth. Bapak/Ibu Orang Tua/Wali dari:</p>
        <table class="tabel-info">
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
        <p>di Tempat</p>
    </div>

    <div class="penutup">
        <p>{!! nl2br(e($pesan)) !!}</p>
    </div>

    <div class="signature">
        <p>Malang, {{ $tanggalCetak }}</p>
        <p>Kepala Sekolah,</p>
        <br><br><br>
        <p><strong>(Nama Kepala Sekolah)</strong></p>
    </div>

</body>
</html>