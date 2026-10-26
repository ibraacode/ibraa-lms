<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKL - {{ $admission->full_name }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; padding: 2cm; color: #000; }
        .kop { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop img { width: 80px; margin-right: 20px; }
        .kop-text { text-align: center; flex: 1; }
        .kop-text h2 { margin: 0; font-size: 16pt; }
        .kop-text h3 { margin: 0; font-size: 14pt; }
        .kop-text p { margin: 2px 0; font-size: 10pt; }
        h1 { text-align: center; text-decoration: underline; font-size: 16pt; margin-bottom: 5px; }
        p.nomor { text-align: center; margin-top: 0; margin-bottom: 30px; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        .info table td { padding: 4px; vertical-align: top; }
        .ttd { margin-top: 50px; float: right; text-align: center; width: 250px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align:right; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 10px 20px; background:#2F80ED; color:#fff; border:none; border-radius:6px; cursor:pointer; font-family: sans-serif; font-weight: bold;">🖨️ Cetak / PDF</button>
    </div>

    {{-- KOP SURAT --}}
    <div class="kop">
        @if(\App\Models\Setting::get('school_logo'))
            <img src="{{ public_path('storage/' . \App\Models\Setting::get('school_logo')) }}" alt="Logo">
        @endif
        <div class="kop-text">
            <h2>PEMERINTAH PROVINSI</h2>
            <h2>{{ strtoupper(\App\Models\Setting::get('school_name', 'NAMA SEKOLAH')) }}</h2>
            <p>{{ \App\Models\Setting::get('school_address') }}</p>
            <p>Telp: {{ \App\Models\Setting::get('school_phone') }}</p>
        </div>
    </div>

    <h1>SURAT KETERANGAN LULUS</h1>
    <p class="nomor">Nomor: SKL/{{ date('Y') }}/{{ $admission->registration_number }}</p>

    <div class="info">
        <p>Berdasarkan hasil seleksi Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran {{ \App\Models\Setting::get('active_academic_year') }}, dengan ini menerangkan bahwa:</p>
        
        <table style="margin-top: 15px;">
            <tr><td style="width: 200px;"><b>Nama Lengkap</b></td><td>: {{ $admission->full_name }}</td></tr>
            <tr><td><b>NISN</b></td><td>: {{ $admission->nisn }}</td></tr>
            <tr><td><b>Tempat, Tanggal Lahir</b></td><td>: {{ $admission->birth_place }}, {{ $admission->birth_date->isoFormat('D MMMM Y') }}</td></tr>
            <tr><td><b>No. Pendaftaran</b></td><td>: {{ $admission->registration_number }}</td></tr>
            <tr><td><b>Asal Sekolah</b></td><td>: {{ $admission->previous_school }}</td></tr>
            <tr><td><b>Jurusan Diterima</b></td><td>: <b>{{ $admission->chosen_major }}</b></td></tr>
        </table>

        <p style="margin-top: 20px;">Berdasarkan hasil seleksi, yang bersangkutan dinyatakan <b>LULUS</b> sebagai calon peserta didik baru di sekolah kami.</p>
        <p>Surat keterangan ini bersifat sementara dan berlaku hingga dikeluarkannya Surat Keputusan resmi dari sekolah.</p>
        <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd">
        <p>{{ \App\Models\Setting::get('school_address') ? explode(',', \App\Models\Setting::get('school_address'))[0] : 'Kota' }}, {{ now()->isoFormat('D MMMM Y') }}</p>
        <p>Kepala Sekolah,</p>
        <br><br><br>
        <p><b><u>(....................................)</u></b></p>
        <p>NIP. ................................</p>
    </div>
</body>
</html>