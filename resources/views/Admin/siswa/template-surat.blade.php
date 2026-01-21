<!DOCTYPE html>
<html>
<head>
    <title>{{ $jenisSurat }} - {{ $siswa->name }}</title>
    <style>
        /* Optimasi Halaman */
        @page {
            margin: 1cm 1.5cm; /* Mengatur margin kertas agar lebih luas */
        }
        
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; /* Sedikit diperkecil dari 12pt */
            line-height: 1.3; /* Mengurangi spasi antar baris */
            margin: 0;
            padding: 0;
        }

        .header { 
            text-align: center; 
            border-bottom: 2px solid black; 
            padding-bottom: 5px; 
            margin-bottom: 15px; 
        }

        .title { font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        
        .content { margin-top: 10px; }
        
        /* Mengatur spasi paragraf agar tidak terlalu renggang */
        p { margin-top: 5px; margin-bottom: 5px; text-align: justify; }

        .table-info { width: 100%; margin: 10px 0; }
        .table-info td { padding: 2px 0; }

        .box-poin { 
            border: 2px solid black; 
            padding: 8px; 
            text-align: center; 
            font-weight: bold; 
            margin: 15px 0; 
            background-color: #f8f8f8;
        }

        .signature { 
            margin-top: 30px; /* Dikurangi agar naik ke atas */
            float: right; /* Menggunakan float agar lebih hemat ruang */
            width: 250px;
            text-align: center;
        }

        .clear { clear: both; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="header">
        <div class="title">PEMERINTAH PROVINSI JAWA TIMUR</div>
        <div class="title">DINAS PENDIDIKAN</div>
        <div class="title" style="font-size: 14pt;">SMK ANTARTIKA 1 SIDOARJO</div>
        <div style="font-size: 9pt; font-style: italic;">
            Jl. Siwalanpanji No. 06, Buduran, Sidoarjo. Telp: (031) 8962512
        </div>
    </div>

    {{-- ISI SURAT --}}
    <div class="content">
        <div style="text-align: center; margin-bottom: 15px;">
            <span style="font-weight: bold; text-decoration: underline; font-size: 12pt; text-transform: uppercase;">{{ $jenisSurat }}</span><br>
            <span>Nomor: {{ $nomorSurat }}</span>
        </div>

        <p>Yth. Orang Tua / Wali Murid dari <strong>{{ $siswa->name }}</strong>,</p>
        <p>Di Tempat</p>
        
        <p>Dengan hormat,</p>
        <p>Berdasarkan catatan kedisiplinan siswa pada Sistem Informasi BK SMK Antartika 1 Sidoarjo, kami memberitahukan data siswa sebagai berikut:</p>

        <table class="table-info">
            <tr>
                <td width="120">Nama Siswa</td>
                <td width="10">:</td>
                <td><strong>{{ $siswa->name }}</strong></td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{ $siswa->biodataSiswa->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kelas / Jurusan</td>
                <td>:</td>
                <td>{{ $siswa->biodataSiswa->kelas->nama_kelas ?? '-' }} / {{ $siswa->biodataSiswa->kelas->jurusan->singkatan ?? '-' }}</td>
            </tr>
        </table>

        <p>Siswa tersebut di atas telah mencapai akumulasi poin pelanggaran tata tertib sekolah:</p>

        <div class="box-poin">
            TOTAL AKUMULASI: {{ $totalPoin }} POIN
        </div>

        <p>
            Sehubungan dengan hal tersebut, sekolah menerbitkan <strong>{{ $jenisSurat }}</strong>. 
            Kami mengharapkan Bapak/Ibu dapat memberikan perhatian dan pembinaan intensif di rumah agar siswa yang bersangkutan dapat memperbaiki perilaku dan mentaati peraturan sekolah.
        </p>

        <p>Demikian surat ini kami sampaikan untuk menjadi perhatian. Terima kasih atas kerjasama Bapak/Ibu.</p>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="signature">
        <p>Sidoarjo, {{ $tanggalCetak }}</p>
        <p>Guru Bimbingan Konseling,</p>
        <br><br><br>
        <p><strong>{{ Auth::user()->name }}</strong></p>
        <p>NIP. ...........................</p>
    </div>
    
    <div class="clear"></div>

</body>
</html>