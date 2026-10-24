<x-layouts.dashboard title="Laporan Kehadiran — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Laporan Kehadiran Bulanan</h1>

    <form method="GET" class="bg-card border border-border rounded-xl p-4 mb-6">
        <div class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-sm mb-1">Kelas</label>
                <select name="classroom_id" required class="px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Bulan</label>
                <select name="month" class="px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Tahun</label>
                <input type="number" name="year" value="{{ $year }}" class="px-3 py-2 rounded-lg border border-border bg-background text-sm w-24">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm">Tampilkan</button>
            @if($report)
                <a href="{{ route('admin.attendance.report.print', ['classroom_id' => $classroomId, 'month' => $month, 'year' => $year]) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm">🖨️ Cetak</a>
            @endif
        </div>
    </form>

    @if($report)
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3 text-center">Hadir</th>
                        <th class="px-4 py-3 text-center">Izin</th>
                        <th class="px-4 py-3 text-center">Sakit</th>
                        <th class="px-4 py-3 text-center">Alpa</th>
                        <th class="px-4 py-3 text-center font-bold">Total Sesi</th>
                        <th class="px-4 py-3 text-center font-bold text-primary">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($report as $row)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $row['student']->name }}</td>
                            <td class="px-4 py-3 text-center text-green-600 font-bold">{{ $row['counts']['hadir'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600">{{ $row['counts']['izin'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600">{{ $row['counts']['sakit'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600">{{ $row['counts']['alpa'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $row['total'] }}</td>
                            <td class="px-4 py-3 text-center font-bold text-primary">
                                {{ $row['total'] > 0 ? round(($row['counts']['hadir'] / $row['total']) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.dashboard>