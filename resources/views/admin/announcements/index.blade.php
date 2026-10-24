<x-layouts.dashboard title="Pengumuman — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Pengumuman</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Buat Pengumuman</h2>
            <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Judul</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Isi Pengumuman</label>
                    <textarea name="content" rows="4" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm mb-1">Ditujukan Kepada</label>
                    <select name="target_role" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="semua">Semua Pengguna</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Publikasikan</button>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
            @forelse($announcements as $a)
                <div class="bg-card border border-border rounded-xl p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-primary">{{ $a->title }}</h3>
                        <span class="text-xs bg-muted px-2 py-1 rounded-md">{{ $a->created_at->format('d M Y') }}</span>
                    </div>
                    <p class="text-sm text-foreground/80 whitespace-pre-wrap mb-3">{{ $a->content }}</p>
                    <p class="text-xs text-foreground/50 border-t border-border pt-2">Ditujukan: <b class="uppercase">{{ $a->target_role }}</b> | Oleh: {{ $a->author->name }}</p>
                </div>
            @empty
                <div class="bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                    Belum ada pengumuman.
                </div>
            @endforelse
            <div class="mt-4">{{ $announcements->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>