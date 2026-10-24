<x-layouts.dashboard title="Rekap Nilai — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Rekap Nilai (Rapor)</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($schedules as $sch)
            <a href="{{ route('teacher.gradebook.show', $sch->id) }}" class="bg-card border border-border rounded-xl p-5 hover:border-primary transition block">
                <h3 class="font-bold text-lg text-primary mb-1">{{ $sch->subject->name }}</h3>
                <p class="text-sm font-medium">{{ $sch->classroom->name }}</p>
                <p class="text-xs text-foreground/50 mt-2">{{ $sch->day }}, {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}</p>
            </a>
        @empty
            <div class="col-span-full bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                Anda belum memiliki jadwal mengajar.
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>