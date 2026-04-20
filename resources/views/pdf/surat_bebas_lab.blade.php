<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Bebas Laboratorium - {{ $peminjaman->nama_peminjam }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; padding: 20px 65px 40px 80px; }
        .garis-kop { border: none; border-top: 4px double #000; margin: 8px 0 16px 0; }
        .judul { text-align: center; margin-bottom: 3px; }
        .judul h2 { font-size: 13pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; letter-spacing: 0.5px; }
        .nomor { text-align: center; font-size: 11pt; margin-bottom: 18px; text-decoration: underline; }
        .pembuka { text-align: justify; line-height: 1.8; margin-bottom: 10px; }
        table.data-mhs { margin: 4px 0 14px 30px; border-collapse: collapse; }
        table.data-mhs td { padding: 2px 6px 2px 0; vertical-align: top; font-size: 12pt; line-height: 1.7; }
        table.data-mhs td.label { width: 130px; }
        table.data-mhs td.sep   { width: 12px; }
        .kewajiban-intro { text-align: justify; line-height: 1.8; margin-bottom: 6px; }
        ol.kewajiban { margin: 0 0 16px 52px; line-height: 1.9; }
        ol.kewajiban li { text-align: justify; }
        .penutup { text-align: justify; line-height: 1.8; margin-bottom: 20px; }
    </style>
</head>
<body>

    {{-- KOP SURAT - pakai tabel agar logo dan teks sejajar rata tengah --}}
    <table style="width:100%; border-collapse:collapse; padding-bottom:8px;">
        <tr>
            <td style="width:120px; text-align:center; vertical-align:middle;">
                @php $logoPath = public_path('images/favicon.ico'); @endphp
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="Logo UIN Ar-Raniry" style="width:100px; height:100px; object-fit:contain;">
                @else
                    <div style="width:100px; height:100px; border:1.5px solid #000; font-size:7pt; text-align:center; line-height:100px;">LOGO UIN</div>
                @endif
            </td>
            <td style="text-align:center; vertical-align:middle; line-height:1.45;">
                <div style="font-size:11pt;">KEMENTERIAN AGAMA</div>
                <div style="font-size:14pt; font-weight:bold; text-transform:uppercase;">UNIVERSITAS ISLAM NEGERI AR-RANIRY BANDA ACEH</div>
                <div style="font-size:12pt; font-weight:bold; text-transform:uppercase;">LABORATORIUM PROGRAM STUDI KIMIA</div>
                <div style="font-size:11pt; font-weight:bold; text-transform:uppercase;">FAKULTAS SAINS &amp; TEKNOLOGI</div>
                <div style="font-size:9pt;">Jl. Syeikh Abdur Rauf Kopelma Darussalam Banda Aceh</div>
                <div style="font-size:9pt;">Telepon: 0651-7552921 &ndash; 7551857 &nbsp;&nbsp; Fax. 0651-7552922</div>
                <div style="font-size:9pt;">Web: www.fst.ar-raniry.ac.id</div>
            </td>
        </tr>
    </table>

    <hr class="garis-kop">

    {{-- JUDUL --}}
    <div class="judul">
        <h2>Surat Keterangan Bebas Laboratorium</h2>
    </div>
    <div class="nomor">
        Nomor: B-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/Un.08/KIM/PP.00.9/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/20-
    </div>

    {{-- PEMBUKA --}}
    <p class="pembuka">
        Yang bertanda tangan di bawah ini, Kepala Laboratorium Program Studi Kimia pada
        Fakultas Sains dan Teknologi, menerangkan bahwa:
    </p>

    {{-- DATA MAHASISWA --}}
    <table class="data-mhs">
        <tr><td class="label">Nama</td><td class="sep">:</td><td>{{ $peminjaman->nama_peminjam }}</td></tr>
        <tr><td class="label">NIM</td><td class="sep">:</td><td>{{ $peminjaman->nim_peminjam }}</td></tr>
        <tr><td class="label">Program Studi</td><td class="sep">:</td><td>Kimia</td></tr>
        <tr><td class="label">Keperluan</td><td class="sep">:</td><td>Syarat Kelulusan</td></tr>
    </table>

    {{-- KEWAJIBAN --}}
    <p class="kewajiban-intro">
        Dengan ini menyatakan bahwa mahasiswa yang bersangkutan telah memenuhi seluruh
        kewajiban administrasi laboratorium, meliputi:
    </p>
    <ol class="kewajiban">
        <li>Tidak memiliki pinjaman alat laboratorium.</li>
        <li>Tidak memiliki tunggakan biaya praktikum atau denda kerusakan alat.</li>
        <li>Telah menyerahkan laporan praktikum/penelitian yang diwajibkan.</li>
        <li>Telah membersihkan area kerja (meja praktikum/loker) yang digunakan.</li>
    </ol>

    {{-- PENUTUP --}}
    <p class="penutup">
        Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan
        sebagaimana mestinya.
    </p>

    {{-- TANGGAL + TTD kanan pakai tabel --}}
    <table style="width:100%; margin-top:10px; border-collapse:collapse;">
        <tr>
            <td style="width:56%;"></td>
            <td style="width:44%; text-align:center; line-height:1.8;">
                Banda Aceh, {{ \Carbon\Carbon::parse($peminjaman->surat_bebas_lab_diterbitkan_at)->translatedFormat('d F Y') }}<br>
                <strong>Mengetahui/Menyetujui,</strong><br>
                Kepala Laboratorium / Laboran,
            </td>
        </tr>
        <tr>
            <td style="height:70px;"></td>
            <td style="height:70px;"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div style="width:200px; border-bottom:1px solid #000; margin:0 auto;"></div>
                <div style="width:200px; margin:3px auto 0; font-size:11pt;">NIP/NIK.</div>
            </td>
        </tr>
    </table>

</body>
</html>