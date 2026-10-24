<x-layouts.dashboard title="Jadwal Pelajaran — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Jadwal Pelajaran</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tambah Jadwal</h2>
            <form method="POST" action="{{ route('admin.academic.schedules.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Kelas</label>
                    <select name="classroom_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih --</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Mata Pelajaran</label>
                    <select name="subject_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih --</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Guru Pengampu</label>
                    <select name="teacher_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Hari</label>
                    <select name="day" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm mb-1">Mulai</label>
                        <input type="time" name="start_time" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Selesai</label>
                        <input type="time" name="end_time" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan Jadwal</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-4 py-3">Hari</th>
                            <th class="px-4 py-3">Jam</th>
                            <th class="px-4 py-3">Kelas</th>
                            <th class="px-4 py-3">Mapel</th>
                            <th class="px-4 py-3">Guru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($schedules as $sch)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $sch->day }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}</td>
                                <td class="px-4 py-3">{{ $sch->classroom->name }}</td>
                                <td class="px-4 py-3">{{ $sch->subject->name }}</td>
                                <td class="px-4 py-3">{{ $sch->teacher->name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Belum ada jadwal.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border">{{ $schedules->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>