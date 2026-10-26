<x-layouts.dashboard title="Catat Pelanggaran — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Catat Pelanggaran</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.violations.types') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Jenis Pelanggaran</a>
            <a href="{{ route('admin.violations.recap') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Rekap Poin</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Input Pelanggaran</h2>
            <form method="POST" action="{{ route('admin.violations.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Pilih Siswa</label>
                    <select name="student_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->nis }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Jenis Pelanggaran</label>
                    <select name="violation_type_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih --</option>
                        @foreach($types as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} (-{{ $t->points }} Poin)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Tanggal Kejadian</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Catatan Tambahan</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan Pelanggaran</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <div class="p-4 border-b border-border bg-muted/30">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS siswa..." class="flex-1 px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm">Cari</button>
                </form>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3">Pelanggaran</th>
                        <th class="px-4 py-3 text-center">Poin</th>
                        <th class="px-4 py-3">Pelapor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($violations as $v)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $v->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium">{{ $v->student->name }}</td>
                            <td class="px-4 py-3">{{ $v->violationType->name }}</td>
                            <td class="px-4 py-3 text-center text-red-600 font-bold">-{{ $v->violationType->points }}</td>
                            <td class="px-4 py-3 text-xs text-foreground/60">{{ $v->reportedBy->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Belum ada catatan pelanggaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $violations->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>