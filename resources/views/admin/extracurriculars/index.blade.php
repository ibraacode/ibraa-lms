<x-layouts.dashboard title="Ekstrakurikuler — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Ekstrakurikuler</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tambah Ekskul</h2>
            <form method="POST" action="{{ route('admin.extracurriculars.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Nama Ekskul</label>
                    <input type="text" name="name" required placeholder="Pramuka / Basket" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Pembina (Opsional)</label>
                    <select name="advisor_id" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm mb-1">Hari</label>
                        <input type="text" name="schedule_day" placeholder="Jumat" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Jam</label>
                        <input type="time" name="schedule_time" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan</button>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
            @forelse($extracurriculars as $ex)
                <div class="bg-card border border-border rounded-xl p-5">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-lg text-primary">{{ $ex->name }}</h3>
                            <p class="text-sm text-foreground/60">Pembina: {{ $ex->advisor->name ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs px-2 py-1 rounded-md font-bold {{ $ex->is_open ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $ex->is_open ? 'PENDAFTARAN BUKA' : 'DITUTUP' }}
                            </span>
                            <p class="text-xs text-foreground/50 mt-1">{{ $ex->members_count }} Anggota</p>
                        </div>
                    </div>
                    <p class="text-sm text-foreground/80 mb-3">{{ $ex->description ?? 'Tidak ada deskripsi.' }}</p>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('admin.extracurriculars.members', $ex->id) }}" class="text-xs font-medium bg-muted px-3 py-1.5 rounded-md hover:bg-primary hover:text-white">Lihat Anggota</a>
                        <form method="POST" action="{{ route('admin.extracurriculars.toggle-open', $ex->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-medium bg-muted px-3 py-1.5 rounded-md hover:bg-primary hover:text-white">
                                {{ $ex->is_open ? 'Tutup Pendaftaran' : 'Buka Pendaftaran' }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                    Belum ada ekskul.
                </div>
            @endforelse
            <div class="mt-4">{{ $extracurriculars->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>