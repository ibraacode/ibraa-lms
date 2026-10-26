<x-layouts.dashboard title="Rekap Poin — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Rekap Poin Pelanggaran (T.A {{ $currentYear }})</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.violations.types') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Jenis Pelanggaran</a>
            <a href="{{ route('admin.violations.index') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Catat Pelanggaran</a>
        </div>
    </div>

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-muted/50 border-b border-border">
                <tr>
                    <th class="px-4 py-3">Nama Siswa</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3 text-center">Total Poin Pelanggaran</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($students as $s)
                    <tr class="{{ $s->total_points >= 100 ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                        <td class="px-4 py-3 font-medium">{{ $s->name }}</td>
                        <td class="px-4 py-3">{{ $s->studentProfile->classroom->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-lg font-bold {{ $s->total_points > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $s->total_points }} Poin
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($s->total_points >= 100)
                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded-md">SANGAT BERAT</span>
                            @elseif($s->total_points >= 50)
                                <span class="px-2 py-1 bg-orange-500 text-white text-xs font-bold rounded-md">PERINGATAN</span>
                            @elseif($s->total_points > 0)
                                <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded-md">CATATAN</span>
                            @else
                                <span class="px-2 py-1 bg-green-500 text-white text-xs font-bold rounded-md">BERSIH</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-foreground/50">Belum ada siswa aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.dashboard>