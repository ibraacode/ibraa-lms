<x-layouts.dashboard title="Presensi Manual — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Presensi Manual</h1>
    <p class="text-sm text-foreground/60 mb-4">Gunakan fitur ini untuk mengisi presensi jika guru lupa membuka sesi.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($classrooms as $c)
            <a href="{{ route('admin.attendance.manual.classroom', $c->id) }}" class="bg-card border border-border rounded-xl p-5 hover:border-primary transition">
                <h3 class="font-bold text-lg text-primary">{{ $c->name }}</h3>
                <p class="text-sm text-foreground/60">{{ $c->students_count }} siswa</p>
            </a>
        @empty
            <div class="col-span-full bg-card border border-border rounded-xl p-8 text-center text-foreground/50">Belum ada kelas.</div>
        @endforelse
    </div>
</x-layouts.dashboard>