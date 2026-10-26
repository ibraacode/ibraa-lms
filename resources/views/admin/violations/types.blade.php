<x-layouts.dashboard title="Jenis Pelanggaran — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Jenis Pelanggaran</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.violations.index') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Catat Pelanggaran</a>
            <a href="{{ route('admin.violations.recap') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Rekap Poin</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tambah Jenis</h2>
            <form method="POST" action="{{ route('admin.violations.types.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Nama Pelanggaran</label>
                    <input type="text" name="name" required placeholder="Terlambat Masuk" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Poin (Angka)</label>
                    <input type="number" name="points" required min="1" placeholder="5" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Keterangan (Opsional)</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama Pelanggaran</th>
                        <th class="px-4 py-3 text-center">Poin</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($types as $t)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $t->name }}</td>
                            <td class="px-4 py-3 text-center text-red-600 font-bold">-{{ $t->points }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.violations.types.destroy', $t->id) }}" onsubmit="return confirm('Hapus jenis ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-foreground/50">Belum ada jenis pelanggaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $types->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>