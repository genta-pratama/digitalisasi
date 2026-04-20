<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Peminjaman - {{ $nomor_surat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; padding: 35px 55px; }
        .kop { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 15px; overflow: hidden; }
        .kop img { float: left; width: 72px; height: 72px; object-fit: contain; margin-right: 12px; }
        .kop-placeholder { float: left; width: 72px; height: 72px; border: 1.5px solid #000; font-size: 7pt; text-align: center; margin-right: 12px; }
        .kop-text { text-align: center; line-height: 1.45; }
        .kop-text .kementerian  { font-size: 11pt; }
        .kop-text .universitas  { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .laboratorium { font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .fakultas     { font-size: 11pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .sub          { font-size: 9pt; }
        .judul { text-align: center; margin: 18px 0 4px; }
        .judul h2 { font-size: 13pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .nomor-surat { text-align: center; font-size: 11pt; margin-bottom: 18px; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.info td { padding: 3px 6px; font-size: 12pt; vertical-align: top; }
        table.info td:first-child { width: 33%; }
        table.info td:nth-child(2) { width: 4%; }
        table.barang { width: 100%; border-collapse: collapse; margin: 10px 0 15px; }
        table.barang th { background-color: #d9d9d9; border: 1px solid #000; padding: 6px 8px; text-align: center; font-size: 11pt; }
        table.barang td { border: 1px solid #000; padding: 5px 8px; font-size: 11pt; vertical-align: top; }
        table.barang td.center { text-align: center; }
        .penutup { line-height: 1.8; text-align: justify; margin-top: 10px; }
        .penutup p { margin-bottom: 8px; }
        .catatan { margin-top: 25px; border-top: 1px solid #555; padding-top: 6px; font-size: 10pt; color: #333; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop">
        @php $logoPath = public_path('images/logo-uin.png'); @endphp
        @if(file_exists($logoPath))
            <img src="{{ $logoPath }}" alt="Logo UIN Ar-Raniry">
        @else
            <div class="kop-placeholder">LOGO<br>UIN</div>
        @endif
        <div class="kop-text">
            <div class="kementerian">KEMENTERIAN AGAMA</div>
            <div class="universitas">UNIVERSITAS ISLAM NEGERI AR-RANIRY BANDA ACEH</div>
            <div class="laboratorium">LABORATORIUM PROGRAM STUDI KIMIA</div>
            <div class="fakultas">FAKULTAS SAINS &amp; TEKNOLOGI</div>
            <div class="sub">Jl. Syeikh Abdur Rauf Kopelma Darussalam Banda Aceh</div>
            <div class="sub">Telepon: 0651-7552921 &ndash; 7551857 &nbsp;&nbsp; Fax. 0651-7552922</div>
            <div class="sub">Web: www.fst.ar-raniry.ac.id</div>
        </div>
        <div style="clear:both;"></div>
    </div>

    {{-- JUDUL --}}
    <div class="judul">
        <h2>Surat Peminjaman Alat / Bahan Laboratorium</h2>
    </div>
    <div class="nomor-surat">Nomor: {{ $nomor_surat }}</div>

    {{-- INFO PEMINJAM --}}
    <table class="info">
        <tr><td>Nama Peminjam</td><td>:</td><td><strong>{{ $nama_peminjam }}</strong></td></tr>
        <tr><td>NIM</td><td>:</td><td>{{ $nim_peminjam }}</td></tr>
        <tr><td>No. HP</td><td>:</td><td>{{ $no_hp }}</td></tr>
        <tr><td>Tanggal Pengajuan</td><td>:</td><td>{{ $tanggal_pinjam }}</td></tr>
    </table>

    {{-- TABEL BARANG --}}
    <p style="margin-bottom:6px;">Daftar alat/bahan yang dipinjam:</p>
    <table class="barang">
        <thead>
            <tr>
                <th style="width:5%">No.</th>
                <th style="width:15%">Kategori</th>
                <th style="width:50%">Nama Alat / Bahan</th>
                <th style="width:15%">Jumlah</th>
                <th style="width:15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $i => $p)
            @php
                $tipe = class_basename($p->peminjamable_type);
                $labelTipe = match($tipe) {
                    'Alat'            => 'Alat',
                    'BahanPadat'      => 'Bahan Padat',
                    'BahanCairanLama' => 'Bahan Cair',
                    default           => $tipe,
                };
            @endphp
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="center">{{ $labelTipe }}</td>
                <td>{{ $p->peminjamable?->nama ?? '-' }}</td>
                <td class="center">{{ $p->jumlah }}</td>
                <td class="center">{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PENUTUP --}}
    <div class="penutup">
        <p>Surat ini dibuat sebagai bukti resmi pengajuan peminjaman alat/bahan laboratorium
        Program Studi Kimia. Peminjam wajib merawat dan mengembalikan alat/bahan dalam kondisi
        baik. Apabila terjadi kerusakan atau kehilangan, peminjam bertanggung jawab penuh.</p>
        <p>Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    {{-- TANGGAL + TTD — pakai tabel supaya DomPDF bisa posisikan ke kanan --}}
    <table style="width:100%; margin-top:20px; border-collapse:collapse;">
        <tr>
            <td style="width:56%;"></td>
            <td style="width:44%; text-align:center; line-height:1.8;">
                Banda Aceh, {{ $tanggal_cetak }}<br>
                Mengetahui/Menyetujui,<br>
                Kepala Laboratorium / Laboran,
            </td>
        </tr>
        <tr>
            <td style="height:70px;"></td>
            <td style="height:70px;"></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-weight:bold; text-decoration:underline;">
                _________________________
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size:11pt;">NIP/NIK.</td>
        </tr>
    </table>

    {{-- CATATAN --}}
    <div class="catatan">
        <strong>Catatan:</strong>
        Surat dicetak otomatis oleh sistem pada {{ $tanggal_cetak }}.
        Nomor surat: <strong>{{ $nomor_surat }}</strong>.
        Harap tunjukkan surat ini kepada petugas laboratorium saat pengambilan.
    </div>

</body>
</html>