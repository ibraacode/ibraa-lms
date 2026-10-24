<x-layouts.dashboard title="Penilaian PKL — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.internship.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
            <div>
                <h1 class="text-2xl font-bold">{{ $placement->student->name }}</h1>
<p class="text-sm text-foreground/60">{{ $placement->industry?->company_name ?? 'Tempat PKL Belum Diset' }}</p>
            </div>
        </div>
        <a href="{{ route('teacher.internship.print', $placement->student->id) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium">🖨️ Cetak Laporan PKL</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('teacher.internship.grade', $placement->student->id) }}">
        @csrf
        
        {{-- Tabel Jurnal & Presensi --}}
        <div class="bg-card border border-border rounded-xl overflow-hidden mb-4">
            <div class="p-4 border-b border-border"><h2 class="font-semibold">Jurnal & Presensi Harian</h2></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Masuk</th>
                            <th class="px-4 py-3">Pulang</th>
                            <th class="px-4 py-3">Kegiatan</th>
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3 w-24">Nilai</th>
                            <th class="px-4 py-3">Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($logs as $log)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $log->date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ $log->check_in_time ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $log->check_out_time ?? '-' }}</td>
                                <td class="px-4 py-3 text-xs">{{ Str::limit($log->activity, 80) }}</td>
                                <td class="px-4 py-3">
                                    @if($log->check_in_photo)
                                        <a href="{{ asset('storage/' . $log->check_in_photo) }}" target="_blank" class="text-primary text-xs hover:underline">Lihat Foto</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="log_scores[{{ $log->id }}]" value="{{ $log->score }}" min="0" max="100" class="w-full px-2 py-1 rounded border text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="log_notes[{{ $log->id }}]" value="{{ $log->teacher_notes }}" class="w-full px-2 py-1 rounded border text-sm">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-foreground/50">Belum ada jurnal.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Nilai Akhir --}}
        <div class="bg-card border border-border rounded-xl p-6 mb-4">
            <h2 class="font-semibold text-lg mb-4">Nilai Akhir PKL</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm mb-1">Nilai Akhir (0-100)</label>
                    <input type="number" name="final_score" value="{{ $placement->final_score }}" min="0" max="100" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1">Catatan Akhir</label>
                    <input type="text" name="final_notes" value="{{ $placement->final_notes }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                Simpan Penilaian
            </button>
        </div>
    </form>
</x-layouts.dashboard>