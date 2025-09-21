<!DOCTYPE html>
<html>
<head>
    <title>Surat Peringatan - {{ $siswa->name }}</title>
    <style> /* ... CSS sederhana untuk styling surat ... */ </style>
</head>
<body>
    <h1>SURAT PERINGATAN - {{ strtoupper($jenis) }}</h1>
    <p>Yang bertanda tangan di bawah ini, menerangkan bahwa siswa:</p>
    <p><strong>Nama:</strong> {{ $siswa->name }}</p>
    <p><strong>NIS:</strong> {{ $siswa->biodataSiswa->nis }}</p>
    <p><strong>Kelas:</strong> {{ $siswa->biodataSiswa->kelas->nama_lengkap }}</p>
    <p>Telah mendapatkan {{ $jenis }} karena telah mencapai total poin pelanggaran yang melebihi batas ketentuan sekolah.</p>
    <br>
    <p>Sidoarjo, {{ $tanggal }}</p>
</body>
</html>