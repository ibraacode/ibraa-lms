<x-layouts.dashboard title="QR Absensi Harian — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">QR Absensi Harian</h1>
        <a href="{{ route('admin.attendance.recap') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">
            Lihat Rekap Hari Ini
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- QR Masuk --}}
        <div class="bg-card border border-border rounded-xl p-6 text-center">
            <h2 class="font-bold text-xl mb-2 text-primary">QR Masuk</h2>
            <p class="text-sm text-foreground/60 mb-4">Tampilkan ini di gerbang saat pagi hari.</p>
            <div class="bg-white p-4 rounded-xl inline-block mx-auto mb-4">
                {!! QrCode::size(250)->generate($tokenIn->token) !!}
            </div>
            <p class="text-xs text-foreground/40 font-mono break-all">{{ $tokenIn->token }}</p>
        </div>

        {{-- QR Pulang --}}
        <div class="bg-card border border-border rounded-xl p-6 text-center">
            <h2 class="font-bold text-xl mb-2 text-red-500">QR Pulang</h2>
            <p class="text-sm text-foreground/60 mb-4">Tampilkan ini di gerbang saat pulang.</p>
            <div class="bg-white p-4 rounded-xl inline-block mx-auto mb-4">
                {!! QrCode::size(250)->generate($tokenOut->token) !!}
            </div>
            <p class="text-xs text-foreground/40 font-mono break-all">{{ $tokenOut->token }}</p>
        </div>
    </div>
</x-layouts.dashboard>