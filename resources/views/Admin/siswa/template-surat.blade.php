<!DOCTYPE html>
<html>
<head>
    <title>{{ $jenisSurat }} - {{ $siswa->name }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px double black; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 80px; position: absolute; left: 0; top: 0; }
        .title { font-size: 14pt; font-weight: bold; }
        .subtitle { font-size: 12pt; }
        .address { font-size: 10pt; font-style: italic; }
        .content { margin-top: 20px; }
        .table-info { width: 100%; margin-bottom: 10px; }
        .table-info td { vertical-align: top; }
        .signature { margin-top: 50px; text-align: right; }
        .box-poin { border: 1px solid black; padding: 10px; text-align: center; font-weight: bold; margin: 20px 0; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="header">
        {{-- Jika ada logo, uncomment baris bawah dan ganti path --}}
        {{-- <img src="{{ public_path('images/logo-sekolah.png') }}" class="logo"> --}}
        <div class="title">PEMERINTAH PROVINSI JAWA TENGAH</div>
        <div class="title">DINAS PENDIDIKAN DAN KEBUDAYAAN</div>
        <div class="title">SMK NEGERI CONTOH WEBSITE</div>
        <div class="address">Jl. Pendidikan No. 123, Kota Coding, Telp (021) 1234567</div>
    </div>

    {{-- ISI SURAT --}}
    <div class="content">
        <div style="text-align: center; margin-bottom: 20px;">
            <span style="font-weight: bold; text-decoration: underline; font-size: 14pt;">{{ $jenisSurat }}</span><br>
            <span>Nomor: {{ $nomorSurat }}</span>
        </div>

        <p>Yth. Orang Tua / Wali Murid,</p>
        <p>Dengan hormat,</p>
        <p>Berdasarkan data kedisiplinan dan tata tertib sekolah yang berlaku, kami memberitahukan bahwa siswa di bawah ini:</p>

        <table class="table-info">
            <tr>
                <td width="150">Nama Siswa</td>
                <td width="10">:</td>
                <td><strong>{{ $siswa->name }}</strong></td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{ $siswa->biodataSiswa->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $siswa->biodataSiswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>:</td>
                <td>{{ $siswa->biodataSiswa->kelas->jurusan->nama_jurusan ?? '-' }}</td>
            </tr>
        </table>

        <p>Telah mencapai akumulasi poin pelanggaran sebagai berikut:</p>

        <div class="box-poin">
            TOTAL POIN PELANGGARAN SAAT INI: {{ $totalPoin }} POIN
        </div>

        <p>
            Sehubungan dengan hal tersebut, kami memberikan <strong>{{ $jenisSurat }}</strong> kepada siswa yang bersangkutan. 
            Kami memohon kerjasama Bapak/Ibu/Wali Murid untuk memberikan pembinaan lebih lanjut di rumah agar siswa dapat memperbaiki kedisiplinannya.
        </p>

        <p>Demikian surat peringatan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="signature">
        <p>{{ $tanggalCetak }}</p>
        <p>Guru Bimbingan Konseling,</p>
        <br><br><br><br>
        <p><strong>{{ Auth::user()->name }}</strong></p>
        <p>NIP. ...........................</p>
    </div>

</body>
</html>