<x-layouts.dashboard title="Detail Kasus — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('counselor.cases.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
            <div>
                <h1 class="text-2xl font-bold">{{ $case->title }}</h1>
                <p class="text-sm text-foreground/60">
                    {{ $case->student->name }} | Kategori: <b>{{ strtoupper($case->category) }}</b>
                </p>
            </div>
        </div>
        <form method="POST" action="{{ route('counselor.cases.update-status', $case->id) }}" class="flex gap-2">
            @csrf
            <select name="status" class="px-3 py-2 rounded-lg border border-border bg-background text-sm">
                <option value="open" {{ $case->status == 'open' ? 'selected' : '' }}>Baru</option>
                <option value="in_progress" {{ $case->status == 'in_progress' ? 'selected' : '' }}>Proses</option>
                <option value="closed" {{ $case->status == 'closed' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm">Update</button>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Tambah Catatan --}}
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Catat Sesi Konseling</h2>
            <form method="POST" action="{{ route('counselor.cases.add-note', $case->id) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Tanggal Sesi</label>
                    <input type="date" name="session_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Isi Catatan (Rahasia)</label>
                    <textarea name="content" rows="6" required placeholder="Catatan hasil konseling..." class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan Catatan</button>
            </form>
        </div>

        {{-- Daftar Catatan --}}
        <div class="lg:col-span-2 space-y-4">
            <h2 class="font-semibold text-lg">Riwayat Sesi</h2>
            @forelse($case->notes as $note)
                <div class="bg-card border border-border rounded-xl p-5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-sm font-bold text-primary">{{ $note->session_date->format('d F Y') }}</span>
                        <span class="text-xs text-foreground/50">oleh {{ $note->author->name }}</span>
                    </div>
                    <p class="text-sm text-foreground/80 whitespace-pre-wrap">{{ $note->content }}</p>
                </div>
            @empty
                <div class="bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                    Belum ada catatan sesi.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>