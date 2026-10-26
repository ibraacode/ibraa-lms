<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor - {{ auth()->user()->name }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; padding: 1.5cm; color: #000; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 2px 0; }
        .info-table { width: 100%; margin-bottom: 20px; font-size: 11pt; }
        .info-table td { padding: 3px 5px; vertical-align: top; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11pt; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px 8px; }
        .data-table th { background-color: #f0f0f0; text-align: center; }
        .center { text-align: center; }
        .karakter-box { display: flex; gap: 10px; margin-top: 15px; }
        .karakter-box > div { flex: 1; border: 1px solid #000; padding: 10px; }
        .karakter-grade { font-size: 20pt; font-weight: bold; margin-bottom: 5px; }
        .ttd { margin-top: 50px; width: 100%; }
        .ttd td { text-align: center; vertical-align: top; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align:right; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; background:#2F80ED; color:#fff; border:none; border-radius:6px; cursor:pointer; font-family: sans-serif;">🖨️ Cetak Rapor / Simpan PDF</button>
        <a href="{{ route('student.gradebook.index') }}" style="padding: 8px 16px; background:#ccc; color:#333; border:none; border-radius:6px; text-decoration:none; font-family: sans-serif; margin-left:10px;">← Kembali</a>
    </div>

    {{-- KOP SURAT --}}
    <div class="header">
        @if(\App\Models\Setting::get('school_logo'))
            <img src="{{ public_path('storage/' . \App\Models\Setting::get('school_logo')) }}" style="max-height: 80px; float: left;">
        @endif
        <h2>LAPORAN HASIL BELAJAR SISWA</h2>
        <h3>{{ strtoupper(\App\Models\Setting::get('school_name', 'NAMA SEKOLAH')) }}</h3>
        <p style="margin: 0;">{{ \App\Models\Setting::get('school_address') }} | Telp: {{ \App\Models\Setting::get('school_phone') }}</p>
    </div>

    {{-- INFO SISWA --}}
    <table class="info-table">
        <tr>
            <td style="width: 15%;"><b>Nama Siswa</b></td><td style="width: 35%;">: {{ auth()->user()->name }}</td>
            <td style="width: 15%;"><b>Tahun Ajaran</b></td><td>: {{ $currentYear }}</td>
        </tr>
        <tr>
            <td><b>NIS</b></td><td>: {{ auth()->user()->nis ?? '-' }}</td>
            <td><b>Kelas</b></td><td>: {{ auth()->user()->studentProfile->classroom->name ?? '-' }}</td>
        </tr>
    </table>

    {{-- TABEL NILAI --}}
    <h4 style="margin-bottom: 5px;">A. Nilai Akademik</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Mata Pelajaran</th>
                <th>Guru Pengampu</th>
                <th>Jumlah Tugas</th>
                <th>Nilai Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grades as $i => $g)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $g['subject'] }}</td>
                    <td>{{ $g['teacher'] }}</td>
                    <td class="center">{{ $g['assignments_count'] }}</td>
                    <td class="center"><b>{{ $g['average'] ?? '-' }}</b></td>
                </tr>
            @empty
                <tr><td colspan="5" class="center">Belum ada data nilai.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- NILAI KARAKTER --}}
    <h4 style="margin-bottom: 5px; margin-top: 20px;">B. Penilaian Karakter (Sikap)</h4>
    <div class="karakter-box">
        <div>
            <div class="karakter-grade">{{ $characterGrade->spiritual_grade ?? '-' }}</div>
            <b>Sikap Spiritual</b>
            <p style="font-size: 10pt; margin-top: 5px;">{{ $characterGrade->spiritual_notes ?? 'Tidak ada catatan.' }}</p>
        </div>
        <div>
            <div class="karakter-grade">{{ $characterGrade->social_grade ?? '-' }}</div>
            <b>Sikap Sosial</b>
            <p style="font-size: 10pt; margin-top: 5px;">{{ $characterGrade->social_notes ?? 'Tidak ada catatan.' }}</p>
        </div>
    </div>

    {{-- TANDA TANGAN --}}
    <table class="ttd">
        <tr>
            <td style="width: 50%;">
                <p>Mengetahui,<br>Orang Tua / Wali</p>
                <br><br><br>
                <p><u>...........................................</u></p>
            </td>
            <td style="width: 50%;">
                <p>{{ \App\Models\Setting::get('school_address') ? explode(',', \App\Models\Setting::get('school_address'))[0] : 'Kota' }}, {{ now()->isoFormat('D MMMM Y') }}<br>Wali Kelas</p>
                <br><br><br>
                <p><b><u>{{ auth()->user()->studentProfile->classroom->homeroomTeacher->name ?? '...........................' }}</u></b></p>
            </td>
        </tr>
    </table>
</body>
</html>