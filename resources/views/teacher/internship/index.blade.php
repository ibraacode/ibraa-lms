<x-layouts.dashboard title="Bimbingan PKL — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Siswa Bimbingan PKL</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($placements as $p)
            <a href="{{ route('teacher.internship.show', $p->student->id) }}" class="bg-card border border-border rounded-xl p-5 hover:border-primary transition">
                <h3 class="font-bold text-lg text-primary mb-1">{{ $p->student->name }}</h3>
                <p class="text-sm text-foreground/70">{{ $p->industry->company_name ?? 'Belum diset' }}</p>
                <p class="text-xs text-foreground/50 mt-2">Kelas: {{ $p->student->studentProfile->classroom->name ?? '-' }}</p>
                @if($p->final_score)
                    <p class="text-sm font-bold text-green-600 mt-2">Nilai Akhir: {{ $p->final_score }}</p>
                @endif
            </a>
        @empty
            <div class="col-span-full bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                Belum ada siswa bimbingan.
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>