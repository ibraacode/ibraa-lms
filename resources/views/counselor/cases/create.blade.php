<x-layouts.dashboard title="Buka Kasus BK — IbraLMS">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('counselor.cases.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
            <h1 class="text-2xl font-bold">Buka Kasus Baru</h1>
        </div>

        <form method="POST" action="{{ route('counselor.cases.store') }}" class="bg-card border border-border rounded-xl p-6 space-y-4">
            @csrf
            @if ($errors->any())
                <div class="p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
            @endif

            <div>
                <label class="block text-sm font-medium mb-1">Pilih Siswa</label>
                <select name="student_id" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} (NIS: {{ $s->nis ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Judul Kasus</label>
                <input type="text" name="title" required placeholder="Contoh: Kesulitan belajar Matematika" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select name="category" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        <option value="akademik">Akademik</option>
                        <option value="keluarga">Keluarga</option>
                        <option value="sosial">Sosial</option>
                        <option value="pelanggaran">Pelanggaran</option>
                        <option value="karir">Karir</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        <option value="open">Baru Dibuka</option>
                        <option value="in_progress">Sedang Ditangani</option>
                        <option value="closed">Selesai</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">Buka Kasus</button>
        </form>
    </div>
</x-layouts.dashboard>