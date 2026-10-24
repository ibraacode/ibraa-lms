<x-layouts.dashboard title="Tambah Role — IbraLMS">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.roles.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
            <h1 class="text-2xl font-bold">Tambah Role Baru</h1>
        </div>

        <form method="POST" action="{{ route('admin.roles.store') }}" class="bg-card border border-border rounded-xl p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Nama Role</label>
                <input type="text" name="name" required placeholder="contoh: Wakil Kepala Sekolah" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                <p class="text-xs text-foreground/50 mt-1">Akan otomatis diubah ke huruf kecil dengan pemisah tanda hubung (-).</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Hak Akses (Permission)</label>
                <div class="space-y-4 bg-muted/30 p-4 rounded-lg">
                    @foreach($permissions as $group => $items)
                        <div>
                            <h4 class="text-xs font-bold text-primary uppercase tracking-wide mb-2">{{ $group }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($items as $perm)
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="rounded border-border">
                                        {{ $perm->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">Simpan Role</button>
        </form>
    </div>
</x-layouts.dashboard>