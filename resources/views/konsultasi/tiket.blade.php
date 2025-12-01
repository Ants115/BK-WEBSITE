<!DOCTYPE html>
<html>
<head>
    <title>Tiket Konsultasi BK</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .content { margin: 20px; }
        table { width: 100%; }
        .label { font-weight: bold; width: 150px; vertical-align: top; }
        .footer { margin-top: 50px; text-align: right; }
        .ttd { margin-top: 60px; font-weight: bold; text-decoration: underline; }
        .status-box { 
            border: 2px dashed green; 
            padding: 10px; 
            color: green; 
            font-weight: bold; 
            text-align: center; 
            margin-top: 30px; 
            width: 200px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>SISTEM INFORMASI BIMBINGAN KONSELING</h2>
        <p>Bukti Jadwal Temu Konsultasi</p>
    </div>

    <div class="content">
        <p>Dengan ini menerangkan bahwa siswa berikut telah disetujui untuk melakukan konsultasi:</p>

        <br>

        <table cellpadding="5">
            <tr>
                <td class="label">Nama Siswa</td>
                <td>: {{ $konsultasi->siswa->name }}</td>
            </tr>
            <tr>
                <td class="label">NIS</td>
                <td>: {{ $konsultasi->siswa->biodataSiswa->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td>: {{ $konsultasi->siswa->biodataSiswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Guru BK</td>
                <td>: {{ $konsultasi->guru->name }}</td>
            </tr>
            <tr>
                <td class="label">Topik</td>
                <td>: {{ $konsultasi->topik }}</td>
            </tr>
            <tr>
                <td class="label">Hari, Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($konsultasi->jadwal_disetujui)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Pukul</td>
                <td>: {{ \Carbon\Carbon::parse($konsultasi->jadwal_disetujui)->format('H:i') }} WIB</td>
            </tr>
        </table>

        <div class="status-box">
            STATUS: DISETUJUI
        </div>

        <p style="margin-top: 30px; font-size: 12px; font-style: italic; color: #555;">
            * Dokumen ini adalah bukti sah yang digenerate oleh sistem.
            <br>
            * Harap tunjukkan surat ini kepada guru mata pelajaran sebagai izin keluar kelas jika sesi konsultasi berlangsung saat jam pelajaran.
        </p>
    </div>

    <div class="footer">
        <p>Mengetahui,</p>
        <div class="ttd">
            {{ $konsultasi->guru->name }}
        </div>
        <p>Guru Bimbingan Konseling</p>
    </div>

</body>
</html>