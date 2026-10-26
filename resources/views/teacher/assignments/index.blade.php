<x-layouts.dashboard title="Manajemen Tugas — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Manajemen Tugas</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    @php
    $isReadOnly = !\App\Helpers\AcademicYear::isActive();
@endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Buat Tugas --}}
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            @if(!$isReadOnly)
                <h2 class="font-semibold text-lg mb-4">Buat Tugas Baru</h2>
                <form method="POST" action="{{ route('teacher.assignments.store') }}" class="space-y-3">
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
                        <label class="block text-sm mb-1">Judul Tugas</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Deskripsi / Instruksi</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tenggat Waktu</label>
                        <input type="datetime-local" name="due_date" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Nilai Maksimal</label>
                        <input type="number" name="max_score" value="100" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Buat Tugas</button>
                </form>
            @else
                <div class="text-center py-6">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <img src="{{ asset('icons/lock.svg') }}" alt="lock" class="w-8 h-8 opacity-60">
                    </div>
                    <h3 class="font-bold text-yellow-700">Mode Arsip</h3>
                    <p class="text-sm text-yellow-600 mt-2">Anda sedang melihat tahun ajaran lama. Tidak dapat membuat tugas baru.</p>
                </div>
            @endif
        </div>

        {{-- Tabel Tugas --}}
        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Tugas</th>
                        <th class="px-4 py-3">Kelas / Mapel</th>
                        <th class="px-4 py-3">Tenggat</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($assignments as $a)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $a->title }}</td>
                            <td class="px-4 py-3">{{ $a->schedule->classroom->name }} - {{ $a->schedule->subject->name }}</td>
                            <td class="px-4 py-3">{{ $a->due_date->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('teacher.assignments.show', $a->id) }}" class="text-primary hover:underline text-xs font-medium">Lihat →</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-foreground/50">Belum ada tugas.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $assignments->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>