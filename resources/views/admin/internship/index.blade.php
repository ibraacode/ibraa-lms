<x-layouts.dashboard title="Tempat PKL — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Manajemen Tempat PKL (DUDI)</h1>
        <a href="{{ route('admin.internship.assign') }}" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Penempatan Siswa →</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tambah Tempat PKL</h2>
            <form method="POST" action="{{ route('admin.internship.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Nama Perusahaan / Instansi</label>
                    <input type="text" name="company_name" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Alamat</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm mb-1">Contact Person</label>
                    <input type="text" name="contact_person" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">No. Telepon</label>
                    <input type="text" name="phone" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Jurusan (Opsional)</label>
                    <input type="text" name="major_code" placeholder="TKJ / AKL / dll" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama Perusahaan</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3">Jurusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($placements as $p)
                        <tr>
                            <td class="px-4 py-3 font-medium">
                                {{ $p->company_name }}
                                <p class="text-xs text-foreground/50 font-normal">{{ Str::limit($p->address, 50) }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $p->contact_person ?? '-' }}<br><span class="text-xs text-foreground/50">{{ $p->phone }}</span></td>
                            <td class="px-4 py-3 uppercase text-xs font-bold text-primary">{{ $p->major_code ?? 'SEMUA' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-foreground/50">Belum ada tempat PKL.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $placements->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>