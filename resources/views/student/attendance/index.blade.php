<x-layouts.dashboard title="Presensi Saya — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Presensi Saya</h1>

    {{-- Kartu Rekap --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60 mb-1">Hadir</p>
            <p class="text-2xl font-bold text-green-600">{{ $summary['hadir'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60 mb-1">Izin</p>
            <p class="text-2xl font-bold text-blue-600">{{ $summary['izin'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60 mb-1">Sakit</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $summary['sakit'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60 mb-1">Alpa</p>
            <p class="text-2xl font-bold text-red-600">{{ $summary['alpa'] }}</p>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="p-4 border-b border-border">
            <h2 class="font-semibold">Riwayat Kehadiran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Mata Pelajaran</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($records as $r)
                        <tr>
                            <td class="px-4 py-3">{{ $r->session->date->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $r->session->schedule->subject->name }}</td>
                            <td class="px-4 py-3">{{ $r->session->schedule->classroom->name }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = [
                                        'hadir' => 'bg-green-100 text-green-700',
                                        'izin' => 'bg-blue-100 text-blue-700',
                                        'sakit' => 'bg-yellow-100 text-yellow-700',
                                        'alpa' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-md text-xs font-medium {{ $colors[$r->status] ?? '' }}">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-foreground/60">{{ $r->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Belum ada riwayat kehadiran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">{{ $records->links() }}</div>
    </div>
</x-layouts.dashboard>