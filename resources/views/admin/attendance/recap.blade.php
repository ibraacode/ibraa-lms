<x-layouts.dashboard title="Rekap Absensi Harian — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Rekap Absensi Harian</h1>
            <p class="text-sm text-foreground/60">{{ \Carbon\Carbon::parse($today)->format('d F Y') }}</p>
        </div>
        <a href="{{ route('admin.attendance.qr') }}" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">
            Tampilkan QR
        </a>
    </div>

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-muted/50 border-b border-border">
                <tr>
                    <th class="px-4 py-3">Nama Siswa</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Jam Masuk</th>
                    <th class="px-4 py-3">Jam Pulang</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($attendances as $att)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $att->student->name }}</td>
                        <td class="px-4 py-3">{{ $att->student->studentProfile->classroom->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-green-600 font-medium">{{ $att->check_in_time ?? '-' }}</td>
                        <td class="px-4 py-3 text-red-600 font-medium">{{ $att->check_out_time ?? '-' }}</td>
                        <td class="px-4 py-3 uppercase text-xs font-bold">{{ $att->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Belum ada siswa yang absen hari ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.dashboard>