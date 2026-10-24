<x-layouts.dashboard title="Isi Presensi — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('teacher.attendance.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <div>
            <h1 class="text-2xl font-bold">{{ $session->schedule->subject->name }} - {{ $session->schedule->classroom->name }}</h1>
            <p class="text-sm text-foreground/60">{{ $session->date->format('d F Y') }} | {{ \Carbon\Carbon::parse($session->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->schedule->end_time)->format('H:i') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('teacher.attendance.update', $session->id) }}">
        @csrf
        
        <div class="bg-card border border-border rounded-xl overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-4 py-3 w-12">No</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3">NIS</th>
                            <th class="px-4 py-3">Status Kehadiran</th>
                            <th class="px-4 py-3">Catatan (Opsional)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($records as $index => $r)
                            <tr>
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium">{{ $r->student->name }}</td>
                                <td class="px-4 py-3">{{ $r->student->nis ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <select name="status[{{ $r->id }}]" class="px-3 py-1.5 rounded-lg border border-border bg-background text-sm w-full">
                                        <option value="hadir" {{ $r->status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="izin" {{ $r->status === 'izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="sakit" {{ $r->status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="alpa" {{ $r->status === 'alpa' ? 'selected' : '' }}>Alpa (Tanpa Keterangan)</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="note[{{ $r->id }}]" value="{{ $r->note }}" placeholder="Keterangan..." class="px-3 py-1.5 rounded-lg border border-border bg-background text-sm w-full">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Tidak ada siswa di kelas ini. (Pastikan siswa sudah dimasukkan ke kelas).</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($records->isNotEmpty())
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                    Simpan Presensi
                </button>
            </div>
        @endif
    </form>
</x-layouts.dashboard>