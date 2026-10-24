<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat — {{ $letter->letter_number }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; color: #000; padding: 2cm; }
        .kop { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop img { width: 80px; margin-right: 20px; }
        .kop-text { text-align: center; flex: 1; }
        .kop-text h2 { margin: 0; font-size: 16pt; }
        .kop-text h3 { margin: 0; font-size: 14pt; }
        .kop-text p { margin: 2px 0; font-size: 10pt; }
        h1.judul { text-align: center; text-decoration: underline; font-size: 14pt; margin-bottom: 2px; }
        p.nomor { text-align: center; margin-top: 0; margin-bottom: 25px; }
        .content { white-space: pre-wrap; text-align: justify; }
        .ttd { margin-top: 40px; float: right; text-align: center; width: 250px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align:right; margin-bottom: 10px;">
        <button onclick="window.print()" style="padding: 8px 16px; background:#7c3aed; color:#fff; border:none; border-radius:6px; cursor:pointer;">🖨️ Cetak / PDF</button>
    </div>

    {{-- Kop Surat --}}
    <div class="kop">
        @if(\App\Models\Setting::get('school_logo'))
            <img src="{{ asset('storage/' . \App\Models\Setting::get('school_logo')) }}" alt="Logo">
        @endif
        <div class="kop-text">
            <h2>PEMERINTAH PROVINSI</h2>
            <h2>{{ strtoupper(\App\Models\Setting::get('school_name', 'NAMA SEKOLAH')) }}</h2>
            <p>{{ \App\Models\Setting::get('school_address') }}</p>
            <p>Telp: {{ \App\Models\Setting::get('school_phone') }}</p>
        </div>
    </div>

    {{-- Judul --}}
    <h1 class="judul">{{ strtoupper($letter->template->name) }}</h1>
    <p class="nomor">Nomor: {{ $letter->letter_number }}</p>

    {{-- Isi --}}
    <div class="content">{{ $letter->content }}</div>

    {{-- Tanda Tangan --}}
    <div class="ttd">
        <p>{{ \App\Models\Setting::get('school_address', 'Kota') }}, {{ $letter->issued_date->isoFormat('D MMMM Y') }}</p>
        <p>Kepala Sekolah,</p>
        <br><br><br>
        <p><b><u>(...................................)</u></b></p>
        <p>NIP. ..........................</p>
    </div>
</body>
</html>