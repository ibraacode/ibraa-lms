<x-layouts.dashboard title="Ekstrakurikuler — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Ekstrakurikuler</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($extracurriculars as $ex)
            <div class="bg-card border border-border rounded-xl p-5">
                <h3 class="font-bold text-lg text-primary">{{ $ex->name }}</h3>
                <p class="text-sm text-foreground/60 mb-2">Pembina: {{ $ex->advisor->name ?? '-' }}</p>
                <p class="text-sm text-foreground/80 mb-3">{{ $ex->description ?? 'Tidak ada deskripsi.' }}</p>
                
                <div class="flex justify-between items-center text-xs mb-3">
                    <span class="bg-muted px-2 py-1 rounded-md">{{ $ex->members_count }} Anggota</span>
                    <span class="{{ $ex->is_open ? 'text-green-600' : 'text-red-500' }} font-bold">{{ $ex->is_open ? 'Buka' : 'Tutup' }}</span>
                </div>

                @if(in_array($ex->id, $myMemberships))
                    <div class="text-center w-full py-1.5 bg-green-100 text-green-700 rounded-md text-sm font-bold">
                        ✓ Terdaftar
                    </div>
                @elseif($ex->is_open)
                    <form method="POST" action="{{ route('student.extracurriculars.join', $ex->id) }}">
                        @csrf
                        <button type="submit" class="w-full py-1.5 bg-primary text-primary-foreground rounded-md text-sm font-medium">Daftar</button>
                    </form>
                @else
                    <div class="text-center w-full py-1.5 bg-muted text-foreground/50 rounded-md text-sm">Ditutup</div>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                Belum ada ekskul yang tersedia.
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>