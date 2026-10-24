<x-layouts.dashboard title="Ekskul Saya — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Ekstrakurikuler yang Saya Bina</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($extracurriculars as $ex)
            <a href="{{ route('teacher.extracurriculars.show', $ex->id) }}" class="bg-card border border-border rounded-xl p-5 hover:border-primary transition">
                <h3 class="font-bold text-lg text-primary mb-1">{{ $ex->name }}</h3>
                <p class="text-sm text-foreground/60">{{ $ex->members_count }} Anggota</p>
            </a>
        @empty
            <div class="col-span-full bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                Anda belum ditunjuk sebagai pembina ekskul.
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>