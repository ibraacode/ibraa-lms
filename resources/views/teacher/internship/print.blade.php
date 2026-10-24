<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan PKL - {{ $placement->student->name }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; padding: 2cm; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt; }
        th, td { border: 1px solid #333; padding: 5px; }
        th { background: #f0f0f0; }
        .photo { max-width: 80px; max-height: 80px; }
        .header { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align:right; margin-bottom: 10px;">
        <button onclick="window.print()" style="padding: 8px 16px; background:#7c3aed; color:#fff; border:none; border-radius:6px; cursor:pointer;">🖨️ Cetak / PDF</button>
    </div>

    <div class="header">
        <h2>LAPORAN PRAKTIK KERJA LAPANGAN (PKL)</h2>
        <h3>{{ strtoupper(\App\Models\Setting::get('school_name', 'NAMA SEKOLAH')) }}</h3>
    </div>

    <table style="border: none; margin-bottom: 20px;">
        <tr><td style="border: none; width: 150px;">Nama Siswa</td><td style="border: none;">: {{ $placement->student->name }}</td></tr>
        <tr><td style="border: none;">NIS</td><td style="border: none;">: {{ $placement->student->nis }}</td></tr>
        <tr><td style="border: none;">Kelas</td><td style="border: none;">: {{ $placement->student->studentProfile->classroom->name ?? '-' }}</td></tr>
<tr><td style="border: none;">Tempat PKL</td><td style="border: none;">: {{ $placement->industry?->company_name ?? '-' }}</td></tr>
      <tr><td style="border: none;">Guru Pembimbing</td><td style="border: none;">: {{ $placement->teacher->name ?? '-' }}</td></tr>
    </table>

    <h3>Jurnal & Presensi Harian</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Kegiatan</th>
                <th>Foto Masuk</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $i => $log)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $log->date->format('d/m/Y') }}</td>
                    <td class="center">{{ $log->check_in_time ?? '-' }}</td>
                    <td class="center">{{ $log->check_out_time ?? '-' }}</td>
                    <td>{{ $log->activity }}</td>
                    <td class="center">
                        @if($log->check_in_photo && file_exists(public_path('storage/' . $log->check_in_photo)))
                            <img src="{{ public_path('storage/' . $log->check_in_photo) }}" class="photo">
                        @else
                            -
                        @endif
                    </td>
                    <td class="center">{{ $log->score ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <p><b>Nilai Akhir PKL:</b> {{ $placement->final_score ?? '-' }}</p>
        <p><b>Catatan Pembimbing:</b> {{ $placement->final_notes ?? '-' }}</p>
    </div>

    <div style="margin-top: 50px; text-align: right;">
        <p>{{ now()->isoFormat('D MMMM Y') }}</p>
        <p>Guru Pembimbing,</p>
        <br><br><br>
        <p><b><u>{{ $placement->teacher->name ?? '.........................' }}</u></b></p>
    </div>
</body>
</html>