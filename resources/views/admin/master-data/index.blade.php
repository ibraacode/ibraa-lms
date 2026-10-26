<x-layouts.dashboard title="Master Data — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Master Data (Jurusan & Pekerjaan)</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- ============ JURUSAN ============ --}}
        <div class="space-y-4">
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-4">Tambah Jurusan</h2>
                <form method="POST" action="{{ route('admin.master-data.majors.store') }}" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-3 gap-2">
                        <input type="text" name="code" required placeholder="TKJ" class="px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <input type="text" name="name" required placeholder="Teknik Komputer" class="col-span-2 px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <textarea name="description" rows="2" placeholder="Deskripsi (opsional)" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                    <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-bold">Simpan Jurusan</button>
                </form>
            </div>

            <div class="bg-card border border-border rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-left">Nama Jurusan</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($majors as $major)
                            <tr>
                                <td class="px-4 py-3 font-bold">{{ $major->code }}</td>
                                <td class="px-4 py-3">{{ $major->name }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.master-data.majors.destroy', $major->id) }}" onsubmit="return confirm('Hapus jurusan?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 text-xs hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3 border-t border-border">{{ $majors->links() }}</div>
            </div>
        </div>

        {{-- ============ PEKERJAAN ============ --}}
        <div class="space-y-4">
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-4">Tambah Pekerjaan</h2>
                <form method="POST" action="{{ route('admin.master-data.occupations.store') }}" class="space-y-3">
                    @csrf
                    <input type="text" name="name" required placeholder="Contoh: PNS, Karyawan Swasta" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-bold">Simpan Pekerjaan</button>
                </form>
            </div>

            <div class="bg-card border border-border rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama Pekerjaan</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($occupations as $occ)
                            <tr>
                                <td class="px-4 py-3">{{ $occ->name }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.master-data.occupations.destroy', $occ->id) }}" onsubmit="return confirm('Hapus pekerjaan?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 text-xs hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3 border-t border-border">{{ $occupations->links() }}</div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>