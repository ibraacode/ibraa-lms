<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran — {{ $classroom->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background-color: #f0f0f0; }
        .center { text-align: center; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 10px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #7c3aed; color: white; border: none; border-radius: 6px; cursor: pointer;">🖨️ Cetak / Simpan PDF</button>
    </div>
    
    <h1>LAPORAN KEHADIRAN SISWA</h1>
    <p class="subtitle">Kelas: {{ $classroom->name }} | Periode: {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Nama Siswa</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpa</th>
                <th>Total</th>
                <th>% Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $row['student']->name }}</td>
                    <td class="center">{{ $row['counts']['hadir'] }}</td>
                    <td class="center">{{ $row['counts']['izin'] }}</td>
                    <td class="center">{{ $row['counts']['sakit'] }}</td>
                    <td class="center">{{ $row['counts']['alpa'] }}</td>
                    <td class="center">{{ $row['total'] }}</td>
                    <td class="center">{{ $row['total'] > 0 ? round(($row['counts']['hadir'] / $row['total']) * 100, 1) : 0 }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>