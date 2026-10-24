<x-layouts.dashboard title="Jurnal Mengajar — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Jurnal Mengajar</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tulis Jurnal Baru</h2>
            <form method="POST" action="{{ route('teacher.journals.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Pilih Jadwal</label>
                    <select name="schedule_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih Kelas & Mapel --</option>
                        @foreach($schedules as $sch)
                            <option value="{{ $sch->id }}">{{ $sch->subject->name }} - {{ $sch->classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Tanggal Mengajar</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Materi yang Diajarkan</label>
                    <textarea name="material_covered" rows="3" required placeholder="Contoh: Bab 3 - Limit Fungsi" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm mb-1">Catatan Kendala (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: 5 siswa izin lomba, kelas agak berisik" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan Jurnal</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kelas / Mapel</th>
                        <th class="px-4 py-3">Materi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($journals as $j)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $j->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $j->schedule->classroom->name }} - {{ $j->schedule->subject->name }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $j->material_covered }}</p>
                                @if($j->notes)
                                    <p class="text-xs text-red-500 mt-1">Catatan: {{ $j->notes }}</p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-foreground/50">Belum ada jurnal.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $journals->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>