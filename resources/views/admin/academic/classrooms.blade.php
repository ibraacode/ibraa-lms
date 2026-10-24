<x-layouts.dashboard title="Kelas — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Manajemen Kelas</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tambah Kelas</h2>
            <form method="POST" action="{{ route('admin.academic.classrooms.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Nama Kelas</label>
                    <input type="text" name="name" required placeholder="X IPA 1" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Tingkat</label>
                    <input type="text" name="grade_level" required placeholder="X" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Jurusan (Opsional)</label>
                    <input type="text" name="major_code" placeholder="IPA" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Tahun Ajaran</label>
                    <input type="text" name="academic_year" required placeholder="2026/2027" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan</button>
            </form>
        </div>

        <div class="md:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama Kelas</th>
                        <th class="px-4 py-3">Tingkat</th>
                        <th class="px-4 py-3">Jurusan</th>
                        <th class="px-4 py-3">Tahun Ajaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($classrooms as $c)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $c->name }}</td>
                            <td class="px-4 py-3">{{ $c->grade_level }}</td>
                            <td class="px-4 py-3">{{ $c->major_code ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $c->academic_year }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-foreground/50">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $classrooms->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>