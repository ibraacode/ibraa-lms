<x-layouts.dashboard title="Mata Pelajaran — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Mata Pelajaran</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Form Tambah --}}
        <div class="md:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tambah Mapel</h2>
            <form method="POST" action="{{ route('admin.academic.subjects.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Kode Mapel</label>
                    <input type="text" name="code" required placeholder="MTK-01" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Nama Mapel</label>
                    <input type="text" name="name" required placeholder="Matematika" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Jurusan (Opsional)</label>
                    <input type="text" name="major_code" placeholder="IPA / TKJ" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan</button>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="md:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama Mapel</th>
                        <th class="px-4 py-3">Jurusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($subjects as $s)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $s->code }}</td>
                            <td class="px-4 py-3">{{ $s->name }}</td>
                            <td class="px-4 py-3">{{ $s->major_code ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-foreground/50">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $subjects->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>